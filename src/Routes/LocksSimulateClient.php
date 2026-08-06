<?php

namespace Seam\Routes;

use Seam\Http\ResolveActionAttempt;
use Seam\Http\SeamHttpClient;
use Seam\Resources\ActionAttempt;

class LocksSimulateClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Simulates the entry of a code on a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code that you want to simulate entering on a keypad.
     * @param string $device_id ID of the device for which you want to simulate a keypad code entry.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function keypad_code_entry(
        string $code,
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["code"] = $code;
        $request_payload["device_id"] = $device_id;

        $res = $this->client->request(
            "POST",
            "/locks/simulate/keypad_code_entry",
            json: (object) $request_payload,
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Simulates a manual lock action using a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $device_id ID of the device for which you want to simulate a manual lock action using a keypad.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function manual_lock_via_keypad(
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = $this->client->request(
            "POST",
            "/locks/simulate/manual_lock_via_keypad",
            json: (object) $request_payload,
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }
}
