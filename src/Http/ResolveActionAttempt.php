<?php

namespace Seam\Http;

use GuzzleHttp\ClientInterface;
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;
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

        return self::poll(
            $action_attempt,
            $client,
            (float) ($options["timeout"] ?? self::TIMEOUT),
            (float) ($options["polling_interval"] ?? self::POLLING_INTERVAL),
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

            if (self::now() + $polling_interval > $deadline) {
                throw new ActionAttemptTimeoutError($action_attempt, $timeout);
            }

            usleep((int) ($polling_interval * 1000000.0));

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
            $client->request("POST", "/action_attempts/get", [
                "json" => (object) ["action_attempt_id" => $action_attempt_id],
            ]),
        );

        $action_attempt = ActionAttempt::from_json(
            $res->action_attempt ?? null,
        );

        if ($action_attempt === null) {
            throw new \UnexpectedValueException(
                "Seam returned no action attempt for {$action_attempt_id}",
            );
        }

        return $action_attempt;
    }

    private static function now(): float
    {
        return microtime(true);
    }
}
