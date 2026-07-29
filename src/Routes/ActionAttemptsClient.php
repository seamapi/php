<?php

namespace Seam\Routes;

use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;
use Seam\Resources\ActionAttempt;
use Seam\SeamClient;

class ActionAttemptsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Returns a specified [action attempt](https://docs.seam.co/core-concepts/action-attempts).
     *
     * @param string $action_attempt_id ID of the action attempt that you want to get.
     * @return ActionAttempt OK
     */
    public function get(string $action_attempt_id): ActionAttempt
    {
        $request_payload = [];

        if ($action_attempt_id !== null) {
            $request_payload["action_attempt_id"] = $action_attempt_id;
        }

        $res = $this->seam->request(
            "POST",
            "/action_attempts/get",
            json: (object) $request_payload,
        );

        return ActionAttempt::from_json($res->action_attempt);
    }

    /**
     * Returns a list of the [action attempts](https://docs.seam.co/core-concepts/action-attempts) that you specify as an array of `action_attempt_id`s.
     *
     * @param array $action_attempt_ids IDs of the action attempts that you want to retrieve.
     * @param string $device_id ID of the device to filter action attempts by.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @return array OK
     */
    public function list(
        ?array $action_attempt_ids = null,
        ?string $device_id = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($action_attempt_ids !== null) {
            $request_payload["action_attempt_ids"] = $action_attempt_ids;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }

        $res = $this->seam->request(
            "POST",
            "/action_attempts/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ActionAttempt::from_json($r),
            $res->action_attempts,
        );
    }
    public function poll_until_ready(
        string $action_attempt_id,
        float $timeout = 20.0,
    ): ActionAttempt {
        $seam = $this->seam;
        $time_waiting = 0.0;
        $polling_interval = 0.4;
        $action_attempt = $seam->action_attempts->get($action_attempt_id);

        while ($action_attempt->status == "pending") {
            $action_attempt = $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
            );
            if ($time_waiting > $timeout) {
                throw new ActionAttemptTimeoutError($action_attempt, $timeout);
            }
            $time_waiting += $polling_interval;
            usleep($polling_interval * 1000000);
        }

        if ($action_attempt->status == "error") {
            throw new ActionAttemptFailedError($action_attempt);
        }

        return $action_attempt;
    }
}
