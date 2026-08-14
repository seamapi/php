<?php

namespace Seam\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Seam\Version;

/**
 * Builds the Guzzle client a Seam client makes its requests with.
 */
final class ClientFactory
{
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
            // Cloned so that building a client does not mutate a stack the
            // caller may reuse, which would stack this middleware twice.
            $handler = clone $handler;
        } else {
            // A bare handler, such as a MockHandler, is wrapped so the
            // middleware below still applies to it.
            $handler = HandlerStack::create($handler);
        }

        // Unshifted so it sits outside every other middleware and only sees
        // a response none of them could act on: a redirect is followed
        // rather than raised, and a retried request is judged by the
        // response it finally settled on.
        $handler->unshift(ErrorMiddleware::create(), "seam_error");
        RetryMiddleware::add($handler, $retries);

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
     * @return array<string, string>
     */
    private static function sdk_headers(): array
    {
        $version = Version::get();

        return [
            "seam-sdk-name" => "seamapi/php",
            "seam-sdk-version" => $version,
        ];
    }
}
