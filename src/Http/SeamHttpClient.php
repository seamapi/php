<?php

namespace Seam\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Utils;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Seam\Exceptions\HttpApiError;
use Seam\Exceptions\HttpInvalidInputError;
use Seam\Exceptions\HttpUnauthorizedError;
use Seam\Utils\PackageVersion;

/**
 * The HTTP client used by every Seam route client.
 *
 * Wraps a Guzzle client with the SDK headers, the retry middleware, and the
 * mapping from Seam error responses onto Seam exceptions.
 */
class SeamHttpClient
{
    public const LTS_VERSION = "1.0.0";

    /**
     * How many times a failed request is retried unless the caller says
     * otherwise. Matches the other Seam SDKs.
     */
    public const DEFAULT_RETRIES = 2;

    /**
     * The default request timeout in seconds.
     *
     * The other Seam SDKs set no timeout. This SDK keeps one so a hung
     * connection eventually fails; pass `timeout` in $guzzle_options to raise,
     * lower, or disable it (0 disables).
     */
    public const DEFAULT_TIMEOUT = 60.0;

    /**
     * Statuses worth retrying, applied only to idempotent requests.
     */
    private const RETRYABLE_STATUS_CODES = [429, 500, 502, 503, 504];

    /**
     * Retrying a request the server may already have processed can duplicate
     * a write, so a status code is only retried for these methods. Every Seam
     * endpoint is a POST, which means an SDK call is retried on a connection
     * failure but never because of the response status. The other Seam SDKs
     * make the same trade.
     */
    private const IDEMPOTENT_METHODS = [
        "GET",
        "HEAD",
        "OPTIONS",
        "PUT",
        "DELETE",
    ];

    private ClientInterface $client;

    /**
     * @param array<string, string> $auth_headers
     * @param array<string, mixed> $guzzle_options
     */
    public function __construct(
        string $endpoint,
        array $auth_headers,
        array $guzzle_options = [],
        ?int $retries = null,
        ?ClientInterface $client = null,
    ) {
        $this->client =
            $client ??
            new GuzzleClient(
                self::create_config(
                    $endpoint,
                    $auth_headers,
                    $guzzle_options,
                    $retries ?? self::DEFAULT_RETRIES,
                ),
            );
    }

    /**
     * Wraps an already configured Guzzle client. The client carries its own
     * endpoint and authorization, so none are resolved here.
     */
    public static function from_client(ClientInterface $client): self
    {
        return new self("", [], [], null, $client);
    }

    public function get_client(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param array<string, mixed> $auth_headers
     * @param array<string, mixed> $guzzle_options
     * @return array<string, mixed>
     */
    private static function create_config(
        string $endpoint,
        array $auth_headers,
        array $guzzle_options,
        int $retries,
    ): array {
        // The middleware has to be on the handler stack the client is built
        // with: a stack pushed onto after construction does not apply.
        $handler = $guzzle_options["handler"] ?? HandlerStack::create();

        if ($handler instanceof HandlerStack) {
            $handler->push(
                GuzzleRetryMiddleware::factory([
                    "retry_enabled" => $retries > 0,
                    "max_retry_attempts" => $retries,
                    // A connection failure never reached the server, so it is
                    // safe to retry whatever the method. Status codes are
                    // opted into per request, see request().
                    "retry_on_timeout" => true,
                    "retry_on_status" => [],
                ]),
                "seam_retry",
            );
        }

        $headers = array_merge(
            $auth_headers,
            $guzzle_options["headers"] ?? [],
            self::sdk_headers(),
        );

        return array_merge(
            [
                "base_uri" => $endpoint,
                "timeout" => self::DEFAULT_TIMEOUT,
            ],
            $guzzle_options,
            [
                "handler" => $handler,
                "headers" => $headers,
                // Error mapping happens in request(); letting Guzzle throw
                // first would bypass it entirely.
                "http_errors" => false,
            ],
        );
    }

    /**
     * @return array<string, string>
     */
    private static function sdk_headers(): array
    {
        $version = PackageVersion::get();

        return [
            "User-Agent" => "seam-php/" . $version,
            "seam-sdk-name" => "seamapi/php",
            "seam-sdk-version" => $version,
            "seam-lts-version" => self::LTS_VERSION,
        ];
    }

    /**
     * @param array<string, mixed>|object|null $json
     * @param array<string, mixed>|null $query
     */
    public function request(
        string $method,
        string $path,
        mixed $json = null,
        ?array $query = null,
    ): mixed {
        $options = array_filter(
            [
                "json" => $json,
                "query" => $query,
            ],
            fn($option) => $option !== null,
        );

        if (in_array(strtoupper($method), self::IDEMPOTENT_METHODS, true)) {
            $options["retry_on_status"] = self::RETRYABLE_STATUS_CODES;
        }

        $response = $this->client->request($method, $path, $options);
        $status_code = $response->getStatusCode();

        if ($status_code < 200 || $status_code >= 300) {
            $this->handle_error_response(
                new Request($method, $path),
                $response,
            );
        }

        return self::decode_body($response);
    }

    private function handle_error_response(
        RequestInterface $request,
        ResponseInterface $response,
    ): void {
        $status_code = $response->getStatusCode();
        $request_id = self::get_request_id($response);

        if ($status_code === 401) {
            throw new HttpUnauthorizedError($request_id);
        }

        if (!self::is_api_error_response($response)) {
            // Not a Seam error response, so there is nothing to map onto a
            // Seam exception. The other Seam SDKs surface the underlying
            // transport error here too.
            throw BadResponseException::create($request, $response);
        }

        $error = self::decode_body($response)->error;

        if (($error->type ?? null) === "invalid_input") {
            throw new HttpInvalidInputError($error, $status_code, $request_id);
        }

        throw new HttpApiError($error, $status_code, $request_id);
    }

    /**
     * True when the response body is a Seam error envelope, i.e. JSON holding
     * an `error` object with a string `type` and `message`.
     */
    private static function is_api_error_response(
        ResponseInterface $response,
    ): bool {
        if (
            !str_starts_with(
                $response->getHeaderLine("content-type"),
                "application/json",
            )
        ) {
            return false;
        }

        $body = self::decode_body($response);

        if (!is_object($body)) {
            return false;
        }

        $error = $body->error ?? null;

        if (!is_object($error)) {
            return false;
        }

        return is_string($error->type ?? null) &&
            is_string($error->message ?? null);
    }

    private static function decode_body(ResponseInterface $response): mixed
    {
        $body = $response->getBody();
        $body->rewind();
        $contents = $body->getContents();

        if ($contents === "") {
            return null;
        }

        try {
            return Utils::jsonDecode($contents);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    private static function get_request_id(ResponseInterface $response): ?string
    {
        return $response->hasHeader("seam-request-id")
            ? $response->getHeaderLine("seam-request-id")
            : null;
    }
}
