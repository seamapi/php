<?php

namespace Seam\Routes;

use Seam\Http\ResolveActionAttempt;
use Seam\Http\SeamHttpClient;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Device;

class LocksClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public LocksSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
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

        $res = $this->client->request(
            "POST",
            "/locks/configure_auto_lock",
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

        $res = $this->client->request(
            "POST",
            "/locks/get",
            json: (object) $request_payload,
        );

        return Device::from_json($res->device);
    }

    /**
     * Returns a list of all [locks](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type of the locks that you want to list.
     * @param array $device_types Device types of the locks that you want to list.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer of the locks that you want to list.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->client->request(
            "POST",
            "/locks/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

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

        $res = $this->client->request(
            "POST",
            "/locks/lock_door",
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

        $res = $this->client->request(
            "POST",
            "/locks/unlock_door",
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
