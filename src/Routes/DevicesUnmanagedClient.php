<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;
use Seam\Resources\UnmanagedDevice;

class DevicesUnmanagedClient
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
     * Returns a specified [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * You must specify either `device_id` or `name`.
     *
     * @param string $device_id ID of the unmanaged device that you want to get.
     * @param string $name Name of the unmanaged device that you want to get.
     * @return UnmanagedDevice OK
     */
    public function get(
        ?string $device_id = null,
        ?string $name = null,
    ): UnmanagedDevice {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->client->request(
            "POST",
            "/devices/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedDevice::from_json($res->device);
    }

    /**
     * Returns a list of all [unmanaged devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type for which you want to list devices.
     * @param array $device_types Array of device types for which you want to list devices.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer for which you want to list devices.
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
            "/devices/unmanaged/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedDevice::from_json($r),
            $res->devices,
        );
    }

    /**
     * Updates a specified [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices). To convert an unmanaged device to managed, set `is_managed` to `true`.
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * @param string $device_id ID of the unmanaged device that you want to update.
     * @param mixed $custom_metadata Custom metadata that you want to associate with the device. Supports up to 50 JSON key:value pairs.
     * @param bool $is_managed Indicates whether the device is managed. Set this parameter to `true` to convert an unmanaged device to managed.
     * @return void OK
     */
    public function update(
        string $device_id,
        mixed $custom_metadata = null,
        ?bool $is_managed = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }

        $this->client->request(
            "POST",
            "/devices/unmanaged/update",
            json: (object) $request_payload,
        );
    }
}
