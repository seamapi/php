<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Http\ResolveActionAttempt;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Device;

class LocksClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public LocksSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new LocksSimulateClient($client, $defaults);
    }

    /**
     * Configures the auto-lock setting for a specified [lock](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param bool $auto_lock_enabled Whether to enable or disable auto-lock.
     * @param string $device_id ID of the lock for which you want to configure the auto-lock.
     * @param float $auto_lock_delay_seconds Delay in seconds before the lock automatically locks. Required when enabling auto-lock. Must be between 1 and 60.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function configure_auto_lock(
        bool $auto_lock_enabled,
        string $device_id,
        ?float $auto_lock_delay_seconds = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["auto_lock_enabled"] = $auto_lock_enabled;
        $request_payload["device_id"] = $device_id;
        if ($auto_lock_delay_seconds !== null) {
            $request_payload[
                "auto_lock_delay_seconds"
            ] = $auto_lock_delay_seconds;
        }

        $res = Body::decode(
            $this->client->request("POST", "/locks/configure_auto_lock", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Returns a specified [lock](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param string $device_id ID of the lock that you want to get.
     * @param string $name Name of the lock that you want to get.
     * @return Device OK
     * @deprecated Use `/devices/get` instead.
     */
    public function get(?string $device_id = null, ?string $name = null): Device
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = Body::decode(
            $this->client->request("GET", "/locks/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return Device::from_json($res->device);
    }

    /**
     * Returns a list of all [locks](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param string $device_type Device type of the locks that you want to list.
     * @param array $device_types Device types of the locks that you want to list.
     * @param string $manufacturer Manufacturer of the locks that you want to list.
     * @return array OK
     */
    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?string $manufacturer = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }

        $res = Body::decode(
            $this->client->request("POST", "/locks/list", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    /**
     * Locks a [lock](https://docs.seam.co/low-level-apis/smart-locks). See also [Locking and Unlocking Smart Locks](https://docs.seam.co/low-level-apis/smart-locks/lock-and-unlock).
     *
     * @param string $device_id ID of the lock that you want to lock.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function lock_door(
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request("POST", "/locks/lock_door", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Unlocks a [lock](https://docs.seam.co/low-level-apis/smart-locks). See also [Locking and Unlocking Smart Locks](https://docs.seam.co/low-level-apis/smart-locks/lock-and-unlock).
     *
     * @param string $device_id ID of the lock that you want to unlock.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function unlock_door(
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request("POST", "/locks/unlock_door", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }
}
