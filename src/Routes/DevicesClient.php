<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\Device;
use Seam\Resources\DeviceProvider;

class DevicesClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public DevicesSimulateClient $simulate;
    public DevicesUnmanagedClient $unmanaged;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new DevicesSimulateClient($client, $defaults);
        $this->unmanaged = new DevicesUnmanagedClient($client, $defaults);
    }

    /**
     * Returns a specified [device](https://docs.seam.co/core-concepts/devices).
     *
     * You must specify either `device_id` or `name`.
     *
     * @param string $device_id ID of the device that you want to get.
     * @param string $name Name of the device that you want to get.
     * @return Device OK
     */
    public function get(?string $device_id = null, ?string $name = null): Device
    {
        if ($device_id === null && $name === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /devices/get",
            );
        }
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = Body::decode(
            $this->client->request("GET", "/devices/get", [
                "query" => $request_payload,
            ]),
        );

        return Device::from_json(Body::read($res, "device", "/devices/get"));
    }

    /**
     * Returns a list of all [devices](https://docs.seam.co/core-concepts/devices).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param list<string> $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param array<string, string|bool>|\stdClass $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices. Key names cannot contain a period (.). Specify `null` to match a key that is unset. A key given an empty string is omitted from the filter.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param list<string> $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type for which you want to list devices.
     * @param list<string> $device_types Array of device types for which you want to list devices.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer for which you want to list devices.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string|NullValue $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        array|\stdClass|null $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        string|NullValue|null $unstable_location_id = null,
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

        $res = Body::decode(
            $this->client->request("GET", "/devices/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => Device::from_json($r),
            Body::read_list($res, "devices", "/devices/list"),
        );
    }

    /**
     * Returns a list of all device providers.
     *
     * The information that this endpoint returns for each provider includes a set of [capability flags](https://docs.seam.co/capability-guides/device-and-system-capabilities#capability-flags), such as `device_provider.can_remotely_unlock`. If at least one supported device from a provider has a specific capability, the corresponding capability flag is `true`.
     *
     * When you create a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews), you can customize the providers—that is, the brands—that it displays. In the `/connect_webviews/create` request, include the desired set of device provider keys in the `accepted_providers` parameter. See also [Customize the Brands to Display in Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-brands-to-display-in-your-connect-webviews).
     *
     * @param string $provider_category Category for which you want to list providers.
     * @return array OK
     */
    public function list_device_providers(
        ?string $provider_category = null,
    ): array {
        $request_payload = [];

        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }

        $res = Body::decode(
            $this->client->request("GET", "/devices/list_device_providers", [
                "query" => $request_payload,
            ]),
        );

        return array_map(
            fn($r) => DeviceProvider::from_json($r),
            Body::read_list(
                $res,
                "device_providers",
                "/devices/list_device_providers",
            ),
        );
    }

    /**
     * Updates provider-specific metadata for devices.
     *
     * @param list<array<string, mixed>|\stdClass> $devices Array of devices with provider metadata to update
     * @return void OK
     */
    public function report_provider_metadata(array $devices): void
    {
        $request_payload = [];

        $request_payload["devices"] = $devices;

        $this->client->request("POST", "/devices/report_provider_metadata", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates a specified [device](https://docs.seam.co/core-concepts/devices).
     *
     * You can add or change [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) for a device, change the device's name, or [convert a managed device to unmanaged](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * @param string $device_id ID of the device that you want to update.
     * @param bool $backup_access_code_pool_enabled Indicates whether the device's [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is enabled. Set to `false` to disable the pool: Seam stops refilling it and removes any backup codes that have not yet been pulled into active use.
     * @param array<string, string|bool>|\stdClass $custom_metadata Custom metadata that you want to associate with the device. Supports up to 50 JSON key:value pairs, with key names up to 40 characters long that cannot contain a period (.). [Adding custom metadata to a device](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) enables you to store custom information, like customer details or internal IDs from your application. Then, you can [filter devices by the desired metadata](https://docs.seam.co/core-concepts/devices/filtering-devices-by-custom-metadata). Set a key to `null` or to an empty string to remove that key from the custom metadata.
     * @param bool $is_managed Indicates whether the device is managed. To unmanage a device, set `is_managed` to `false`.
     * @param string|NullValue $name Name for the device.
     * @param mixed $properties
     * @return void OK
     */
    public function update(
        string $device_id,
        ?bool $backup_access_code_pool_enabled = null,
        array|\stdClass|null $custom_metadata = null,
        ?bool $is_managed = null,
        string|NullValue|null $name = null,
        mixed $properties = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($backup_access_code_pool_enabled !== null) {
            $request_payload[
                "backup_access_code_pool_enabled"
            ] = $backup_access_code_pool_enabled;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($properties !== null) {
            $request_payload["properties"] = $properties;
        }

        $this->client->request("PATCH", "/devices/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
