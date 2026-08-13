<?php

namespace Seam\Http;

use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Message\RequestInterface;

/**
 * Retries transient failures for idempotent requests.
 *
 * Guzzle re-applies the request timeout to every retry, so the timeout covers
 * each individual attempt rather than the complete sequence of attempts.
 */
final class RetryMiddleware
{
    private const IDEMPOTENT_METHODS = [
        "GET",
        "HEAD",
        "OPTIONS",
        "PUT",
        "DELETE",
    ];

    private const INITIAL_DELAY_SECONDS = 0.2;

    private const JITTER_MULTIPLIER = 1.2;

    public static function add(HandlerStack $handler, int $retries): void
    {
        $handler->push(
            GuzzleRetryMiddleware::factory([
                "retry_enabled" => $retries > 0,
                "max_retry_attempts" => max(0, $retries),
                "retry_on_methods" => self::IDEMPOTENT_METHODS,
                "retry_on_timeout" => true,
                "retry_on_status" => array_merge([429], range(500, 599)),
                // Delay is applied by the callback below so Retry-After can
                // be compared with, rather than replace, the jittered delay.
                "default_retry_multiplier" => 0.0,
                "on_retry_callback" => static function (
                    int $retry_count,
                    float $retry_after,
                    RequestInterface &$request,
                    array &$options,
                ): void {
                    $backoff =
                        self::INITIAL_DELAY_SECONDS *
                        2.0 ** (float) ($retry_count - 1);
                    $jittered_backoff = random_int(
                        (int) ($backoff * 1000.0),
                        (int) (
                            $backoff *
                            self::JITTER_MULTIPLIER *
                            1000.0
                        ),
                    );

                    $options["delay"] = max(
                        $jittered_backoff,
                        (int) ceil($retry_after * 1000.0),
                    );
                },
            ]),
            "seam_retry",
        );
    }
}
