<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\AcsEntrance;
use Seam\Resources\AcsSystem;
use Seam\Resources\AcsUser;
use Seam\Resources\Device;
use Seam\Resources\InstantKey;
use Seam\Resources\UserIdentity;

class UserIdentitiesClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public UserIdentitiesUnmanagedClient $unmanaged;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->unmanaged = new UserIdentitiesUnmanagedClient(
            $client,
            $defaults,
        );
    }

    /**
     * Adds a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * You must specify either `user_identity_id` or `user_identity_key` to identify the user identity.
     *
     * If `user_identity_key` is provided, but the user identity doesn't exist, a new user identity will be created automatically using information from the ACS user.
     *
     * @param string $acs_user_id ID of the access system user that you want to add to the user identity.
     * @param string $user_identity_id ID of the user identity to which you want to add an access system user.
     * @param string $user_identity_key Key of the user identity to which you want to add an access system user.
     * @return void OK
     */
    public function add_acs_user(
        string $acs_user_id,
        ?string $user_identity_id = null,
        ?string $user_identity_key = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_user_id"] = $acs_user_id;
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->client->request("PUT", "/user_identities/add_acs_user", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Creates a new [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param list<string> $acs_system_ids List of access system IDs to associate with the new user identity through access system users. If there's no user with the same email address or phone number in the specified access systems, a new access system user is created. If there is an existing user with the same email or phone number in the specified access systems, the user is linked to the user identity.
     * @param string|NullValue $email_address Unique email address for the new user identity.
     * @param string|NullValue $full_name Full name of the user associated with the new user identity.
     * @param string|NullValue $phone_number Unique phone number for the new user identity in E.164 format (for example, +15555550100).
     * @param string|NullValue $user_identity_key Unique key for the new user identity.
     * @return UserIdentity OK
     */
    public function create(
        ?array $acs_system_ids = null,
        string|NullValue|null $email_address = null,
        string|NullValue|null $full_name = null,
        string|NullValue|null $phone_number = null,
        string|NullValue|null $user_identity_key = null,
    ): UserIdentity {
        $request_payload = [];

        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $res = Body::decode(
            $this->client->request("POST", "/user_identities/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return UserIdentity::from_json(
            Body::read($res, "user_identity", "/user_identities/create"),
        );
    }

    /**
     * Deletes a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This deletes the user identity and all associated resources, including any [credentials](https://docs.seam.co/api/acs/credentials), [acs users](https://docs.seam.co/api/acs/users) and [client sessions](https://docs.seam.co/api/client_sessions).
     *
     * @param string $user_identity_id ID of the user identity that you want to delete.
     * @return void OK
     */
    public function delete(string $user_identity_id): void
    {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $this->client->request("DELETE", "/user_identities/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Generates a new [instant key](https://docs.seam.co/capability-guides/instant-keys) for a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to generate an instant key.
     * @param string $customization_profile_id
     * @param float $max_use_count Maximum number of times the instant key can be used. Default: 1.
     * @return InstantKey OK
     */
    public function generate_instant_key(
        string $user_identity_id,
        ?string $customization_profile_id = null,
        ?float $max_use_count = null,
    ): InstantKey {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($max_use_count !== null) {
            $request_payload["max_use_count"] = $max_use_count;
        }

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/user_identities/generate_instant_key",
                ["json" => (object) $request_payload],
            ),
        );

        return InstantKey::from_json(
            Body::read(
                $res,
                "instant_key",
                "/user_identities/generate_instant_key",
            ),
        );
    }

    /**
     * Returns a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to get.
     * @param string $user_identity_key
     * @return UserIdentity OK
     */
    public function get(
        ?string $user_identity_id = null,
        ?string $user_identity_key = null,
    ): UserIdentity {
        if ($user_identity_id === null && $user_identity_key === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /user_identities/get",
            );
        }
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/get", [
                "query" => $request_payload,
            ]),
        );

        return UserIdentity::from_json(
            Body::read($res, "user_identity", "/user_identities/get"),
        );
    }

    /**
     * Grants a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) access to a specified [device](https://docs.seam.co/core-concepts/devices/).
     *
     * @param string $device_id ID of the managed device to which you want to grant access to the user identity.
     * @param string $user_identity_id ID of the user identity that you want to grant access to a device.
     * @return void OK
     */
    public function grant_access_to_device(
        string $device_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["user_identity_id"] = $user_identity_id;

        $this->client->request(
            "PUT",
            "/user_identities/grant_access_to_device",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Returns a list of all [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $created_before Timestamp by which to limit returned user identities. Returns user identities created before this timestamp.
     * @param string $credential_manager_acs_system_id `acs_system_id` of the credential manager by which you want to filter the list of user identities.
     * @param int $limit Maximum number of records to return per page.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned user identities to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address` or `user_identity_id`.
     * @param list<string> $user_identity_ids Array of user identity IDs by which to filter the list of user identities.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $created_before = null,
        ?string $credential_manager_acs_system_id = null,
        ?int $limit = null,
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?array $user_identity_ids = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
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
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UserIdentity::from_json($r),
            Body::read_list($res, "user_identities", "/user_identities/list"),
        );
    }

    /**
     * Returns a list of all [devices](https://docs.seam.co/core-concepts/devices) associated with a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This includes devices derived from the access grants assigned to the user identity and devices directly linked to the user identity.
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all accessible devices.
     * @return array OK
     */
    public function list_accessible_devices(string $user_identity_id): array
    {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/user_identities/list_accessible_devices",
                ["query" => $request_payload],
            ),
        );

        return array_map(
            fn($r) => Device::from_json($r),
            Body::read_list(
                $res,
                "devices",
                "/user_identities/list_accessible_devices",
            ),
        );
    }

    /**
     * Returns a list of all [ACS entrances](https://docs.seam.co/api/acs/entrances) accessible to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This includes entrances derived from the access grants assigned to the user identity and entrances accessible through ACS users linked to the user identity.
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all accessible entrances.
     * @return array OK
     */
    public function list_accessible_entrances(string $user_identity_id): array
    {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/user_identities/list_accessible_entrances",
                ["query" => $request_payload],
            ),
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            Body::read_list(
                $res,
                "acs_entrances",
                "/user_identities/list_accessible_entrances",
            ),
        );
    }

    /**
     * Returns a list of all [access systems](https://docs.seam.co/low-level-apis/access-systems) associated with a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access systems.
     * @return array OK
     */
    public function list_acs_systems(string $user_identity_id): array
    {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/list_acs_systems", [
                "query" => $request_payload,
            ]),
        );

        return array_map(
            fn($r) => AcsSystem::from_json($r),
            Body::read_list(
                $res,
                "acs_systems",
                "/user_identities/list_acs_systems",
            ),
        );
    }

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management) assigned to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access system users.
     * @return array OK
     */
    public function list_acs_users(string $user_identity_id): array
    {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/list_acs_users", [
                "query" => $request_payload,
            ]),
        );

        return array_map(
            fn($r) => AcsUser::from_json($r),
            Body::read_list(
                $res,
                "acs_users",
                "/user_identities/list_acs_users",
            ),
        );
    }

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $acs_user_id ID of the access system user that you want to remove from the user identity..
     * @param string $user_identity_id ID of the user identity from which you want to remove an access system user.
     * @return void OK
     */
    public function remove_acs_user(
        string $acs_user_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        $request_payload["acs_user_id"] = $acs_user_id;
        $request_payload["user_identity_id"] = $user_identity_id;

        $this->client->request("DELETE", "/user_identities/remove_acs_user", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Revokes access to a specified [device](https://docs.seam.co/core-concepts/devices/) from a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $device_id ID of the managed device to which you want to revoke access from the user identity.
     * @param string $user_identity_id ID of the user identity from which you want to revoke access to a device.
     * @return void OK
     */
    public function revoke_access_to_device(
        string $device_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["user_identity_id"] = $user_identity_id;

        $this->client->request(
            "DELETE",
            "/user_identities/revoke_access_to_device",
            ["query" => $request_payload],
        );
    }

    /**
     * Updates a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to update.
     * @param string|NullValue $email_address Unique email address for the user identity.
     * @param string|NullValue $full_name Full name of the user associated with the user identity.
     * @param string|NullValue $phone_number Unique phone number for the user identity.
     * @param string|NullValue $user_identity_key Unique key for the user identity.
     * @return void OK
     */
    public function update(
        string $user_identity_id,
        string|NullValue|null $email_address = null,
        string|NullValue|null $full_name = null,
        string|NullValue|null $phone_number = null,
        string|NullValue|null $user_identity_key = null,
    ): void {
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->client->request("PATCH", "/user_identities/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
