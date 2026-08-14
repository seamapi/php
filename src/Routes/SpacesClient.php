<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\Batch;
use Seam\Resources\Space;

class SpacesClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Adds [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) to a specific space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add entrances.
     * @return void OK
     */
    public function add_acs_entrances(
        array $acs_entrance_ids,
        string $space_id,
    ): void {
        $request_payload = [];

        $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        $request_payload["space_id"] = $space_id;

        $this->client->request("PUT", "/spaces/add_acs_entrances", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Adds a [connected account](https://docs.seam.co/core-concepts/connected-accounts) to a specific space.
     *
     * @param string $connected_account_id ID of the connected account that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add the connected account.
     * @return void OK
     */
    public function add_connected_account(
        string $connected_account_id,
        string $space_id,
    ): void {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;
        $request_payload["space_id"] = $space_id;

        $this->client->request("PUT", "/spaces/add_connected_account", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Adds devices to a specific space.
     *
     * @param array $device_ids IDs of the devices that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add devices.
     * @return void OK
     */
    public function add_devices(array $device_ids, string $space_id): void
    {
        $request_payload = [];

        $request_payload["device_ids"] = $device_ids;
        $request_payload["space_id"] = $space_id;

        $this->client->request("PUT", "/spaces/add_devices", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Creates a new space.
     *
     * @param string $name Name of the space that you want to create.
     * @param array $acs_entrance_ids IDs of the entrances that you want to add to the new space.
     * @param array $connected_account_ids IDs of connected accounts to associate with the new space. Persisted on seam.location_third_party_account so the UI can show which provider account(s) a space came from.
     * @param mixed $customer_data Reservation/stay-related defaults for the space.
     * @param string $customer_key Customer key for which you want to create the space.
     * @param array $device_ids IDs of the devices that you want to add to the new space.
     * @param string $space_key Unique key for the space within the workspace.
     * @return Space OK
     */
    public function create(
        string $name,
        ?array $acs_entrance_ids = null,
        ?array $connected_account_ids = null,
        mixed $customer_data = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        $request_payload["name"] = $name;
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = Body::decode(
            $this->client->request("POST", "/spaces/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return Space::from_json($res->space);
    }

    /**
     * Deletes a space.
     *
     * @param string $space_id ID of the space that you want to delete.
     * @return void OK
     */
    public function delete(string $space_id): void
    {
        $request_payload = [];

        $request_payload["space_id"] = $space_id;

        $this->client->request("DELETE", "/spaces/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Gets a space.
     *
     * @param string $space_id ID of the space that you want to get.
     * @param string $space_key Unique key of the space that you want to get.
     * @return Space OK
     */
    public function get(
        ?string $space_id = null,
        ?string $space_key = null,
    ): Space {
        if ($space_id === null && $space_key === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /spaces/get",
            );
        }
        $request_payload = [];

        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/spaces/get", [
                "query" => $request_payload,
            ]),
        );

        return Space::from_json($res->space);
    }

    /**
     * Gets all related resources for one or more Spaces.
     *
     * @param array $exclude
     * @param array $include
     * @param array $space_ids IDs of the spaces that you want to get along with their related resources.
     * @param array $space_keys Keys of the spaces that you want to get along with their related resources.
     * @return Batch OK
     */
    public function get_related(
        ?array $exclude = null,
        ?array $include = null,
        ?array $space_ids = null,
        ?array $space_keys = null,
    ): Batch {
        if (
            $exclude === null &&
            $include === null &&
            $space_ids === null &&
            $space_keys === null
        ) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /spaces/get_related",
            );
        }
        $request_payload = [];

        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }
        if ($space_ids !== null) {
            $request_payload["space_ids"] = $space_ids;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }

        $res = Body::decode(
            $this->client->request("POST", "/spaces/get_related", [
                "json" => (object) $request_payload,
            ]),
        );

        return Batch::from_json($res->batch);
    }

    /**
     * Returns a list of all spaces.
     *
     * @param string $customer_key Customer key for which you want to list spaces.
     * @param float $limit Maximum number of records to return per page.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned spaces to include all records that satisfy a partial match using `name`, `space_key`, or `customer_key`.
     * @param string $space_key Filter spaces by space_key.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $customer_key = null,
        ?float $limit = null,
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?string $space_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

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
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/spaces/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Space::from_json($r), $res->spaces);
    }

    /**
     * Removes [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) from a specific space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove entrances.
     * @return void OK
     */
    public function remove_acs_entrances(
        array $acs_entrance_ids,
        string $space_id,
    ): void {
        $request_payload = [];

        $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        $request_payload["space_id"] = $space_id;

        $this->client->request("POST", "/spaces/remove_acs_entrances", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Removes a [connected account](https://docs.seam.co/core-concepts/connected-accounts) from a specific space.
     *
     * @param string $connected_account_id ID of the connected account that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove the connected account.
     * @return void OK
     */
    public function remove_connected_account(
        string $connected_account_id,
        string $space_id,
    ): void {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;
        $request_payload["space_id"] = $space_id;

        $this->client->request("DELETE", "/spaces/remove_connected_account", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Removes devices from a specific space.
     *
     * @param array $device_ids IDs of the devices that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove devices.
     * @return void OK
     */
    public function remove_devices(array $device_ids, string $space_id): void
    {
        $request_payload = [];

        $request_payload["device_ids"] = $device_ids;
        $request_payload["space_id"] = $space_id;

        $this->client->request("POST", "/spaces/remove_devices", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates an existing space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to set for the space. If specified, this will replace all existing entrances.
     * @param mixed $customer_data Reservation/stay-related defaults for the space. Only the keys you provide are updated; omit a key to leave it unchanged. Pass null on a key to clear it.
     * @param array $device_ids IDs of the devices that you want to set for the space. If specified, this will replace all existing devices.
     * @param string $name Name of the space.
     * @param string $space_id ID of the space that you want to update.
     * @param string $space_key Unique key of the space that you want to update.
     * @return Space OK
     */
    public function update(
        ?array $acs_entrance_ids = null,
        mixed $customer_data = null,
        ?array $device_ids = null,
        ?string $name = null,
        ?string $space_id = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = Body::decode(
            $this->client->request("PATCH", "/spaces/update", [
                "json" => (object) $request_payload,
            ]),
        );

        return Space::from_json($res->space);
    }
}
