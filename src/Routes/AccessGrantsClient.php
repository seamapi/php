<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\AccessGrant;
use Seam\Resources\Batch;

class AccessGrantsClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public AccessGrantsUnmanagedClient $unmanaged;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->unmanaged = new AccessGrantsUnmanagedClient($client, $defaults);
    }

    /**
     * Creates a new [Access Grant](https://docs.seam.co/use-cases/granting-access/access-grants). Access Grants are the default and recommended way to grant a user access to any physical space, irrespective of the locking hardware. They work with both standalone smart locks (using `device_ids`) and access control systems (using `acs_entrance_ids` or `space_ids`), and can issue PIN codes, key cards, and mobile keys through a single request.
     *
     * @param list<array<string, mixed>|\stdClass> $requested_access_methods
     * @param string $access_grant_key Unique key for the access grant within the workspace.
     * @param list<string> $acs_entrance_ids Set of IDs of the [entrances](https://docs.seam.co/api/acs/systems/list) to which access is being granted.
     * @param string $customization_profile_id ID of the customization profile to apply to the Access Grant and its access methods.
     * @param list<string> $device_ids Set of IDs of the [devices](https://docs.seam.co/api/devices/list) to which access is being granted.
     * @param string|NullValue $ends_at Date and time at which the validity of the new grant ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param mixed $location
     * @param list<string> $location_ids
     * @param string|NullValue $name Name for the access grant.
     * @param string $reservation_key Reservation key for the access grant.
     * @param list<string> $space_ids Set of IDs of existing spaces to which access is being granted.
     * @param list<string> $space_keys Set of keys of existing spaces to which access is being granted.
     * @param string $starts_at Date and time at which the validity of the new grant starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param mixed $user_identity When used, creates a new user identity with the given details, and grants them access.
     * @param string $user_identity_id ID of user identity for whom access is being granted.
     * @return AccessGrant OK
     */
    public function create(
        array $requested_access_methods,
        ?string $access_grant_key = null,
        ?array $acs_entrance_ids = null,
        ?string $customization_profile_id = null,
        ?array $device_ids = null,
        string|NullValue|null $ends_at = null,
        mixed $location = null,
        ?array $location_ids = null,
        string|NullValue|null $name = null,
        ?string $reservation_key = null,
        ?array $space_ids = null,
        ?array $space_keys = null,
        ?string $starts_at = null,
        mixed $user_identity = null,
        ?string $user_identity_id = null,
    ): AccessGrant {
        $request_payload = [];

        $request_payload[
            "requested_access_methods"
        ] = $requested_access_methods;
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($location !== null) {
            $request_payload["location"] = $location;
        }
        if ($location_ids !== null) {
            $request_payload["location_ids"] = $location_ids;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
        }
        if ($space_ids !== null) {
            $request_payload["space_ids"] = $space_ids;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($user_identity !== null) {
            $request_payload["user_identity"] = $user_identity;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("POST", "/access_grants/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return AccessGrant::from_json(
            Body::read($res, "access_grant", "/access_grants/create"),
        );
    }

    /**
     * Delete an Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to delete.
     * @return void OK
     */
    public function delete(string $access_grant_id): void
    {
        $request_payload = [];

        $request_payload["access_grant_id"] = $access_grant_id;

        $this->client->request("DELETE", "/access_grants/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Get an Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to get.
     * @param string $access_grant_key Unique key of Access Grant to get.
     * @return AccessGrant OK
     */
    public function get(
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
    ): AccessGrant {
        if ($access_grant_id === null && $access_grant_key === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /access_grants/get",
            );
        }
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/access_grants/get", [
                "query" => $request_payload,
            ]),
        );

        return AccessGrant::from_json(
            Body::read($res, "access_grant", "/access_grants/get"),
        );
    }

    /**
     * Gets all related resources for one or more Access Grants.
     *
     * @param list<string> $access_grant_ids IDs of the access grants that you want to get along with their related resources.
     * @param list<string> $access_grant_keys Keys of the access grants that you want to get along with their related resources.
     * @param list<string> $exclude
     * @param list<string> $include
     * @return Batch OK
     */
    public function get_related(
        ?array $access_grant_ids = null,
        ?array $access_grant_keys = null,
        ?array $exclude = null,
        ?array $include = null,
    ): Batch {
        if (
            $access_grant_ids === null &&
            $access_grant_keys === null &&
            $exclude === null &&
            $include === null
        ) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /access_grants/get_related",
            );
        }
        $request_payload = [];

        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
        if ($access_grant_keys !== null) {
            $request_payload["access_grant_keys"] = $access_grant_keys;
        }
        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }

        $res = Body::decode(
            $this->client->request("GET", "/access_grants/get_related", [
                "query" => $request_payload,
            ]),
        );

        return Batch::from_json(
            Body::read($res, "batch", "/access_grants/get_related"),
        );
    }

    /**
     * Gets an Access Grant.
     *
     * @param string $access_code_id ID of the access code by which you want to filter the list of Access Grants.
     * @param list<string> $access_grant_ids IDs of the access grants to retrieve.
     * @param string|NullValue $access_grant_key Filter Access Grants by access_grant_key. Use null to filter for Access Grants without an access_grant_key.
     * @param string $acs_entrance_id ID of the entrance by which you want to filter the list of Access Grants.
     * @param string $acs_system_id ID of the access system by which you want to filter the list of Access Grants.
     * @param string $customer_key Customer key for which you want to list access grants.
     * @param string $device_id ID of the device by which you want to filter the list of Access Grants.
     * @param float $limit Numerical limit on the number of access grants to return.
     * @param string $location_id
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $reservation_key Filter Access Grants by reservation_key.
     * @param string $space_id ID of the space by which you want to filter the list of Access Grants.
     * @param string $user_identity_id ID of user identity by which you want to filter the list of Access Grants.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $access_code_id = null,
        ?array $access_grant_ids = null,
        string|NullValue|null $access_grant_key = null,
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?string $customer_key = null,
        ?string $device_id = null,
        ?float $limit = null,
        ?string $location_id = null,
        string|NullValue|null $page_cursor = null,
        ?string $reservation_key = null,
        ?string $space_id = null,
        ?string $user_identity_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($location_id !== null) {
            $request_payload["location_id"] = $location_id;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/access_grants/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AccessGrant::from_json($r),
            Body::read_list($res, "access_grants", "/access_grants/list"),
        );
    }

    /**
     * Adds additional requested access methods to an existing Access Grant.
     *
     * @param string $access_grant_id ID of the Access Grant to add access methods to.
     * @param list<array<string, mixed>|\stdClass> $requested_access_methods Array of requested access methods to add to the access grant.
     * @return AccessGrant OK
     */
    public function request_access_methods(
        string $access_grant_id,
        array $requested_access_methods,
    ): AccessGrant {
        $request_payload = [];

        $request_payload["access_grant_id"] = $access_grant_id;
        $request_payload[
            "requested_access_methods"
        ] = $requested_access_methods;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/access_grants/request_access_methods",
                ["json" => (object) $request_payload],
            ),
        );

        return AccessGrant::from_json(
            Body::read(
                $res,
                "access_grant",
                "/access_grants/request_access_methods",
            ),
        );
    }

    /**
     * Updates an existing Access Grant's time window.
     *
     * @param string $access_grant_id ID of the Access Grant to update. Provide either `access_grant_id` or `access_grant_key`.
     * @param string $access_grant_key Key of the Access Grant to update. Provide either `access_grant_id` or `access_grant_key`.
     * @param string|NullValue $ends_at Date and time at which the validity of the grant ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param string|NullValue $name Display name for the access grant.
     * @param string $starts_at Date and time at which the validity of the grant starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return void OK
     */
    public function update(
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
        string|NullValue|null $ends_at = null,
        string|NullValue|null $name = null,
        ?string $starts_at = null,
    ): void {
        if (
            $access_grant_id === null &&
            $access_grant_key === null &&
            $ends_at === null &&
            $name === null &&
            $starts_at === null
        ) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /access_grants/update",
            );
        }
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->client->request("PATCH", "/access_grants/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
