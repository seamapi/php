<?php

namespace Seam\Http;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Message\RequestInterface;

/**
 * Retries failed requests.
 *
 * Retrying a request the server may already have processed can duplicate a
 * write, so what is retried depends on the method:
 *
 * - A method that is safe to repeat is retried on a retryable status code,
 *   and on any transport error.
 * - Any other method is only retried on a transport error that occurred
 *   before the request could have reached the server, such as a failed
 *   connection. A timeout in particular is not retried, because it may have
 *   fired while waiting on a response to a request the server received and
 *   is still processing.
 */
final class RetryMiddleware
{
    private const RETRYABLE_STATUS_CODES = [429, 500, 502, 503, 504];

    private const IDEMPOTENT_METHODS = [
        "GET",
        "HEAD",
        "OPTIONS",
        "PUT",
        "DELETE",
    ];

    /**
     * The linear backoff applied per attempt already made, matching the
     * default of the retry middleware handling the idempotent methods.
     */
    private const RETRY_DELAY_SECONDS = 1.5;

    /**
     * The curl error for a request that hit a timeout. The timeout may have
     * fired while waiting for the response, after the server received the
     * request.
     */
    private const CURLE_OPERATION_TIMEDOUT = 28;

    /**
     * The errno for a connection reset by the peer, which the retry
     * middleware below also treats as retryable.
     */
    private const ECONNRESET = 104;

    public static function add(HandlerStack $handler, int $retries): void
    {
        $handler->push(self::retry_options_middleware(), "seam_retry_options");
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
        // Pushed last, so it sits inside the middleware above and its
        // retries are invisible to it: each mechanism covers the failures
        // the other does not, and their budgets never combine.
        $handler->push(
            self::transport_retry_middleware($retries),
            "seam_transport_retry",
        );
    }

    /**
     * Configures the retry middleware per request: a method that is safe to
     * repeat opts into status based retries, any other method opts out of
     * its transport error handling, which would retry timeouts as well, and
     * is covered by the transport retry middleware instead.
     */
    private static function retry_options_middleware(): callable
    {
        return static fn(callable $handler): callable => static function (
            RequestInterface $request,
            array $options,
        ) use ($handler): PromiseInterface {
            if (self::is_idempotent($request)) {
                $options["retry_on_status"] = self::RETRYABLE_STATUS_CODES;
            } else {
                $options["retry_on_timeout"] = false;
            }

            return $handler($request, $options);
        };
    }

    /**
     * Retries the transport errors for the methods that are not safe to
     * repeat, skipping anything that looks like a timeout.
     */
    private static function transport_retry_middleware(int $retries): callable
    {
        return static fn(
            callable $handler,
        ): callable => static fn(RequestInterface $request, array $options): PromiseInterface => self::dispatch($handler, $request, $options, $retries);
    }

    private static function dispatch(
        callable $handler,
        RequestInterface $request,
        array $options,
        int $retries,
    ): PromiseInterface {
        return $handler($request, $options)->otherwise(static function (
            \Throwable $reason,
        ) use ($handler, $request, $options, $retries): PromiseInterface {
            $attempts = $options["seam_transport_retries"] ?? 0;

            if (
                !is_int($attempts) ||
                $attempts >= $retries ||
                self::is_idempotent($request) ||
                !self::is_retryable_transport_error($reason)
            ) {
                return Create::rejectionFor($reason);
            }

            $options["seam_transport_retries"] = $attempts + 1;
            $options["delay"] =
                (int) (self::RETRY_DELAY_SECONDS *
                    (float) ($attempts + 1) *
                    1000.0);

            return self::dispatch($handler, $request, $options, $retries);
        });
    }

    private static function is_retryable_transport_error(
        \Throwable $reason,
    ): bool {
        if ($reason instanceof ConnectException) {
            return !self::is_timeout($reason);
        }

        // A response was received, so this is a status error covered by the
        // retry middleware, not a transport error.
        if ($reason instanceof BadResponseException) {
            return false;
        }

        if ($reason instanceof RequestException) {
            $errno = $reason->getHandlerContext()["errno"] ?? null;

            return $errno === self::ECONNRESET;
        }

        return false;
    }

    /**
     * Whether a connection exception is a timeout. The curl errno is
     * decisive when present; a handler without one, such as the stream
     * handler, is judged by its message.
     */
    private static function is_timeout(ConnectException $reason): bool
    {
        $errno = $reason->getHandlerContext()["errno"] ?? null;

        if ($errno !== null) {
            return $errno === self::CURLE_OPERATION_TIMEDOUT;
        }

        return preg_match("/timed?\s*out/i", $reason->getMessage()) === 1;
    }

    private static function is_idempotent(RequestInterface $request): bool
    {
        return in_array(
            strtoupper($request->getMethod()),
            self::IDEMPOTENT_METHODS,
            true,
        );
    }
}
