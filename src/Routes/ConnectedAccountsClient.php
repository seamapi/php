<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\ConnectedAccount;

class ConnectedAccountsClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public ConnectedAccountsSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new ConnectedAccountsSimulateClient(
            $client,
            $defaults,
        );
    }

    /**
     * Deletes a specified [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * Deleting a connected account triggers a `connected_account.deleted` event and removes the connected account and all data associated with the connected account from Seam, including devices, events, access codes, and so on. For every deleted resource, Seam sends a corresponding deleted event, but the resource is not deleted from the provider.
     *
     * For example, if you delete a connected account with a device that has an access code, Seam sends a `connected_account.deleted` event, a `device.deleted` event, and an `access_code.deleted` event, but Seam does not remove the access code from the device.
     *
     * @param string $connected_account_id ID of the connected account that you want to delete.
     * @return void OK
     */
    public function delete(string $connected_account_id): void
    {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;

        $this->client->request("DELETE", "/connected_accounts/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Returns a specified [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param string $connected_account_id ID of the connected account that you want to get.
     * @param string $email Email address associated with the connected account that you want to get.
     * @return ConnectedAccount OK
     */
    public function get(
        ?string $connected_account_id = null,
        ?string $email = null,
    ): ConnectedAccount {
        if ($connected_account_id === null && $email === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /connected_accounts/get",
            );
        }
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }

        $res = Body::decode(
            $this->client->request("GET", "/connected_accounts/get", [
                "query" => $request_payload,
            ]),
        );

        return ConnectedAccount::from_json(
            Body::read($res, "connected_account", "/connected_accounts/get"),
        );
    }

    /**
     * Returns a list of all [connected accounts](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param array<string, string|bool>|\stdClass $custom_metadata_has Custom metadata pairs by which you want to filter connected accounts. Returns connected accounts with `custom_metadata` that contains all of the provided key:value pairs.
     * @param string $customer_key Customer key by which you want to filter connected accounts.
     * @param int $limit Maximum number of records to return per page.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned connected accounts to include all records that satisfy a partial match using `connected_account_id`, `account_type`, `customer_key`, `custom_metadata`, `user_identifier.username`, `user_identifier.email` or `user_identifier.phone`.
     * @param string $space_id ID of the space by which you want to filter connected accounts.
     * @param string $user_identifier_key Your user ID for the user by which you want to filter connected accounts.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        array|\stdClass|null $custom_metadata_has = null,
        ?string $customer_key = null,
        ?int $limit = null,
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
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
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/connected_accounts/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ConnectedAccount::from_json($r),
            Body::read_list(
                $res,
                "connected_accounts",
                "/connected_accounts/list",
            ),
        );
    }

    /**
     * Request a [connected account](https://docs.seam.co/core-concepts/connected-accounts) sync attempt for the specified `connected_account_id`.
     *
     * @param string $connected_account_id ID of the connected account that you want to sync.
     * @return void OK
     */
    public function sync(string $connected_account_id): void
    {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;

        $this->client->request("POST", "/connected_accounts/sync", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates a [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param string $connected_account_id ID of the connected account that you want to update.
     * @param list<string> $accepted_capabilities List of accepted device capabilities that restrict the types of devices that can be connected through this connected account. Valid values are `lock`, `thermostat`, `noise_sensor`, and `access_control`.
     * @param bool $automatically_manage_new_devices Indicates whether newly-added devices should appear as [managed devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     * @param array<string, string|bool>|\stdClass $custom_metadata Custom metadata that you want to associate with the connected account. Entirely replaces the existing custom metadata object. If a new Connect Webview contains custom metadata and is used to reconnect a connected account, the custom metadata from the Connect Webview will entirely replace the entire custom metadata object on the connected account. Supports up to 50 JSON key:value pairs. [Adding custom metadata to a connected account](https://docs.seam.co/core-concepts/connected-accounts/adding-custom-metadata-to-a-connected-account) enables you to store custom information, like customer details or internal IDs from your application. Then, you can [filter connected accounts by the desired metadata](https://docs.seam.co/core-concepts/connected-accounts/filtering-connected-accounts-by-custom-metadata).
     * @param string $customer_key The customer key to associate with this connected account. If provided, the connected account and all resources under the connected account will be moved to this customer. May only be provided if the connected account is not already associated with a customer.
     * @param string $display_name Human-readable name for the connected account, shown in the dashboard. For example, `Booking from Airbnb House 1`.
     * @return void OK
     */
    public function update(
        string $connected_account_id,
        ?array $accepted_capabilities = null,
        ?bool $automatically_manage_new_devices = null,
        array|\stdClass|null $custom_metadata = null,
        ?string $customer_key = null,
        ?string $display_name = null,
    ): void {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;
        if ($accepted_capabilities !== null) {
            $request_payload["accepted_capabilities"] = $accepted_capabilities;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($display_name !== null) {
            $request_payload["display_name"] = $display_name;
        }

        $this->client->request("PATCH", "/connected_accounts/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
