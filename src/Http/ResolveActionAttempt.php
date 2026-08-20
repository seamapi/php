<?php

namespace Seam\Http;

use GuzzleHttp\ClientInterface;
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;
use Seam\InvalidOptionsError;
use Seam\InvalidResponseError;
use Seam\Resources\ActionAttempt;

/**
 * Waits for an action attempt to reach a terminal state.
 *
 * A successful attempt is returned as is, a failed one raises, and a pending
 * one is polled until it finishes or the timeout elapses.
 */
final class ResolveActionAttempt
{
    public const TIMEOUT = 10.0;

    public const POLLING_INTERVAL = 1.0;

    /**
     * @param bool|array{timeout?: float, polling_interval?: float}|null $wait_for_action_attempt
     *
     * @throws InvalidOptionsError If timeout is negative or polling_interval is not greater than zero.
     */
    public static function resolve_action_attempt(
        ActionAttempt $action_attempt,
        ClientInterface $client,
        bool|array|null $wait_for_action_attempt,
    ): ActionAttempt {
        if ($wait_for_action_attempt === false) {
            return $action_attempt;
        }

        $options = is_array($wait_for_action_attempt)
            ? $wait_for_action_attempt
            : [];

        $timeout = (float) ($options["timeout"] ?? self::TIMEOUT);
        $polling_interval =
            (float) ($options["polling_interval"] ?? self::POLLING_INTERVAL);

        if ($timeout < 0.0) {
            throw new InvalidOptionsError(
                "The timeout option must not be negative, got {$timeout}",
            );
        }

        if ($polling_interval <= 0.0) {
            throw new InvalidOptionsError(
                "The polling_interval option must be greater than zero, got {$polling_interval}",
            );
        }

        return self::poll(
            $action_attempt,
            $client,
            $timeout,
            $polling_interval,
        );
    }

    private static function poll(
        ActionAttempt $action_attempt,
        ClientInterface $client,
        float $timeout,
        float $polling_interval,
    ): ActionAttempt {
        $deadline = self::now() + $timeout;

        while (true) {
            if ($action_attempt->status === "success") {
                return $action_attempt;
            }

            if ($action_attempt->status === "error") {
                throw new ActionAttemptFailedError($action_attempt);
            }

            $remaining = $deadline - self::now();

            if ($remaining <= 0.0) {
                throw new ActionAttemptTimeoutError($action_attempt, $timeout);
            }

            usleep((int) (min($polling_interval, $remaining) * 1000000.0));

            $action_attempt = self::get_action_attempt(
                $client,
                $action_attempt->action_attempt_id,
            );
        }
    }

    private static function get_action_attempt(
        ClientInterface $client,
        string $action_attempt_id,
    ): ActionAttempt {
        $res = Body::decode(
            $client->request("GET", "/action_attempts/get", [
                "query" => ["action_attempt_id" => $action_attempt_id],
            ]),
        );

        $action_attempt = ActionAttempt::from_json(
            Body::read($res, "action_attempt", "/action_attempts/get"),
        );

        if ($action_attempt === null) {
            throw new InvalidResponseError(
                "/action_attempts/get",
                "action_attempt",
                "which was empty for {$action_attempt_id}",
            );
        }

        return $action_attempt;
    }

    private static function now(): float
    {
        return microtime(true);
    }
}
