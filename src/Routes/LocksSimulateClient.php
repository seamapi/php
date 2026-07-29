<?php

namespace Seam\Routes;

use Seam\Resources\ActionAttempt;
use Seam\SeamClient;

class LocksSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Simulates the entry of a code on a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code that you want to simulate entering on a keypad.
     * @param string $device_id ID of the device for which you want to simulate a keypad code entry.
     * @return ActionAttempt OK
     */
    public function keypad_code_entry(
        string $code,
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/simulate/keypad_code_entry",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    /**
     * Simulates a manual lock action using a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $device_id ID of the device for which you want to simulate a manual lock action using a keypad.
     * @return ActionAttempt OK
     */
    public function manual_lock_via_keypad(
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/simulate/manual_lock_via_keypad",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}
