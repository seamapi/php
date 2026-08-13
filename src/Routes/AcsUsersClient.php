<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\AcsEntrance;
use Seam\Resources\AcsUser;

class AcsUsersClient
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
     * Adds a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) to a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group to which you want to add an access system user.
     * @param string $acs_user_id ID of the access system user that you want to add to an access group.
     * @return void OK
     */
    public function add_to_access_group(
        string $acs_access_group_id,
        string $acs_user_id,
    ): void {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;
        $request_payload["acs_user_id"] = $acs_user_id;

        $this->client->request("PUT", "/acs/users/add_to_access_group", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Creates a new [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the access system to which you want to add the new access system user.
     * @param string $full_name Full name of the new access system user.
     * @param mixed $access_schedule `starts_at` and `ends_at` timestamps for the new access system user's access. If you specify an `access_schedule`, you may include both `starts_at` and `ends_at`. If you omit `starts_at`, it defaults to the current time. `ends_at` is optional and must be a time in the future and after `starts_at`.
     * @param array $acs_access_group_ids Array of access group IDs to indicate the access groups to which you want to add the new access system user.
     * @param string $email
     * @param string $email_address Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $phone_number Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
     * @param string $user_identity_id ID of the user identity with which you want to associate the new access system user.
     * @return AcsUser OK
     */
    public function create(
        string $acs_system_id,
        string $full_name,
        mixed $access_schedule = null,
        ?array $acs_access_group_ids = null,
        ?string $email = null,
        ?string $email_address = null,
        ?string $phone_number = null,
        ?string $user_identity_id = null,
    ): AcsUser {
        $request_payload = [];

        $request_payload["acs_system_id"] = $acs_system_id;
        $request_payload["full_name"] = $full_name;
        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($acs_access_group_ids !== null) {
            $request_payload["acs_access_group_ids"] = $acs_access_group_ids;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("POST", "/acs/users/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return AcsUser::from_json($res->acs_user);
    }

    /**
     * Deletes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) and invalidates the access system user's [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_system_id ID of the access system that you want to delete. You must provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to delete. You must provide either acs_user_id or user_identity_id
     * @param string $user_identity_id ID of the user identity that you want to delete. You must provide either acs_user_id or user_identity_id. If you provide user_identity_id, you must also provide acs_system_id.
     * @return void OK
     */
    public function delete(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("DELETE", "/acs/users/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Returns a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_user_id ID of the access system user that you want to get. You can only provide acs_user_id or user_identity_id.
     * @param string $acs_system_id ID of the access system that you want to get. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to get. You can only provide acs_user_id or user_identity_id.
     * @return AcsUser OK
     */
    public function get(
        ?string $acs_user_id = null,
        ?string $acs_system_id = null,
        ?string $user_identity_id = null,
    ): AcsUser {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/acs/users/get", [
                "query" => $request_payload,
            ]),
        );

        return AcsUser::from_json($res->acs_user);
    }

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the `acs_system` for which you want to retrieve all access system users.
     * @param string $created_before Timestamp by which to limit returned access system users. Returns users created before this timestamp.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned access system users to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address`, `acs_user_id`, `user_identity_id`, `user_identity_full_name` or `user_identity_phone_number`.
     * @param string $user_identity_email_address Email address of the user identity for which you want to retrieve all access system users.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access system users.
     * @param string $user_identity_phone_number Phone number of the user identity for which you want to retrieve all access system users, in [E.164 format](https://www.itu.int/rec/T-REC-E.164/en) (for example, `+15555550100`).
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $acs_system_id = null,
        ?string $created_before = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $user_identity_email_address = null,
        ?string $user_identity_id = null,
        ?string $user_identity_phone_number = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
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
        if ($user_identity_email_address !== null) {
            $request_payload[
                "user_identity_email_address"
            ] = $user_identity_email_address;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_phone_number !== null) {
            $request_payload[
                "user_identity_phone_number"
            ] = $user_identity_phone_number;
        }

        $res = Body::decode(
            $this->client->request("GET", "/acs/users/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => AcsUser::from_json($r), $res->acs_users);
    }

    /**
     * Lists the [entrances](https://docs.seam.co/api/acs/entrances) to which a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) has access.
     *
     * @param string $acs_system_id ID of the access system for which you want to list accessible entrances. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user for whom you want to list accessible entrances. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity for whom you want to list accessible entrances. You can only provide acs_user_id or user_identity_id.
     * @return array OK
     */
    public function list_accessible_entrances(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/acs/users/list_accessible_entrances",
                ["query" => $request_payload],
            ),
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group from which you want to remove an access system user.
     * @param string $acs_user_id ID of the access system user that you want to remove from an access group. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to remove from an access group. You can only provide acs_user_id or user_identity_id.
     * @return void OK
     */
    public function remove_from_access_group(
        string $acs_access_group_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request(
            "DELETE",
            "/acs/users/remove_from_access_group",
            ["query" => $request_payload],
        );
    }

    /**
     * Revokes access to all [entrances](https://docs.seam.co/api/acs/entrances) for a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the access system for which you want to revoke access. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user for whom you want to revoke access. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity for whom you want to revoke access. You can only provide acs_user_id or user_identity_id.
     * @return void OK
     */
    public function revoke_access_to_all_entrances(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request(
            "POST",
            "/acs/users/revoke_access_to_all_entrances",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * [Suspends](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users#suspend-an-acs-user) a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Suspending an access system user revokes their access temporarily. To restore an access system user's access, you can [unsuspend](https://docs.seam.co/api/acs/users/unsuspend) them.
     *
     * @param string $acs_system_id ID of the access system that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @return void OK
     */
    public function suspend(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("POST", "/acs/users/suspend", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * [Unsuspends](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users#unsuspend-an-acs-user) a specified suspended [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). While [suspending an access system user](https://docs.seam.co/api/acs/users/suspend) revokes their access temporarily, unsuspending the access system user restores their access.
     *
     * @param string $acs_system_id ID of the access system of the user that you want to unsuspend. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to unsuspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to unsuspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @return void OK
     */
    public function unsuspend(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("POST", "/acs/users/unsuspend", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates the properties of a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param mixed $access_schedule `starts_at` and `ends_at` timestamps for the access system user's access. If you specify an `access_schedule`, you may include both `starts_at` and `ends_at`. If you omit `starts_at`, it defaults to the current time. `ends_at` is optional and must be a time in the future and after `starts_at`.
     * @param string $acs_system_id ID of the access system that you want to update. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to update. You can only provide acs_user_id or user_identity_id.
     * @param string $email
     * @param string $email_address Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $full_name Full name of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $hid_acs_system_id ID of the HID access control system associated with the user.
     * @param string $phone_number Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
     * @param string $user_identity_id ID of the user identity that you want to update. You can only provide acs_user_id or user_identity_id. If you provide user_identity_id, you must also provide acs_system_id.
     * @return void OK
     */
    public function update(
        mixed $access_schedule = null,
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $email = null,
        ?string $email_address = null,
        ?string $full_name = null,
        ?string $hid_acs_system_id = null,
        ?string $phone_number = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($hid_acs_system_id !== null) {
            $request_payload["hid_acs_system_id"] = $hid_acs_system_id;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("PATCH", "/acs/users/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
