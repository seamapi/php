<?php

namespace Seam\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Message\RequestInterface;
use Seam\Utils\PackageVersion;

/**
 * Builds the Guzzle client a Seam client makes its requests with.
 */
final class ClientFactory
{
    public const LTS_VERSION = "1.0.0";

    /**
     * How many times a failed request is retried unless the caller says
     * otherwise.
     */
    public const DEFAULT_RETRIES = 2;

    /**
     * The default request timeout in seconds, applied to both connecting and
     * reading.
     */
    public const DEFAULT_TIMEOUT = 30.0;

    private const RETRYABLE_STATUS_CODES = [429, 500, 502, 503, 504];

    /**
     * Retrying a request the server may already have processed can duplicate a
     * write, so a status code only triggers a retry for a method that is safe
     * to repeat. A request that never reached the server is retried whatever
     * the method, since it cannot have had an effect.
     */
    private const IDEMPOTENT_METHODS = [
        "GET",
        "HEAD",
        "OPTIONS",
        "PUT",
        "DELETE",
    ];

    /**
     * @param array<string, string> $auth_headers
     * @param array<string, mixed> $guzzle_options
     */
    public static function create(
        string $endpoint,
        array $auth_headers,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
    ): Client {
        $retries ??= self::DEFAULT_RETRIES;
        $timeout ??= self::DEFAULT_TIMEOUT;

        // Middleware has to be on the handler stack the client is built with:
        // a stack pushed onto after construction does not apply.
        $handler = $guzzle_options["handler"] ?? HandlerStack::create();

        if ($handler instanceof HandlerStack) {
            // Pushed first, so it wraps the retry middleware and only sees the
            // response a request finally settled on.
            $handler->push(ErrorMiddleware::create(), "seam_error");
            $handler->push(
                self::retry_options_middleware(),
                "seam_retry_options",
            );
            $handler->push(
                GuzzleRetryMiddleware::factory([
                    "retry_enabled" => $retries > 0,
                    "max_retry_attempts" => $retries,
                    "retry_on_timeout" => true,
                    // Set per request by the middleware above.
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

        return new Client(
            array_merge(
                [
                    "base_uri" => $endpoint,
                    "timeout" => $timeout,
                    "connect_timeout" => $timeout,
                ],
                $guzzle_options,
                [
                    "handler" => $handler,
                    "headers" => $headers,
                    // ErrorMiddleware raises instead, so that a Seam error
                    // response becomes a Seam exception.
                    "http_errors" => false,
                ],
            ),
        );
    }

    /**
     * Opts a request into status based retries when repeating it is safe.
     */
    private static function retry_options_middleware(): callable
    {
        return static fn(callable $handler): callable => static function (
            RequestInterface $request,
            array $options,
        ) use ($handler): PromiseInterface {
            if (
                in_array(
                    strtoupper($request->getMethod()),
                    self::IDEMPOTENT_METHODS,
                    true,
                )
            ) {
                $options["retry_on_status"] = self::RETRYABLE_STATUS_CODES;
            }

            return $handler($request, $options);
        };
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
}
