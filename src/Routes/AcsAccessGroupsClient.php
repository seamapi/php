<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\AcsAccessGroup;
use Seam\Resources\AcsEntrance;
use Seam\Resources\AcsUser;

class AcsAccessGroupsClient
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
     * @param string $acs_user_id ID of the access system user that you want to add to an access group. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the desired user identity that you want to add to an access group. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the access group membership belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
    public function add_user(
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

        $this->client->request("POST", "/acs/access_groups/add_user", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Deletes a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group that you want to delete.
     * @return void OK
     */
    public function delete(string $acs_access_group_id): void
    {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;

        $this->client->request("POST", "/acs/access_groups/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Returns a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group that you want to get.
     * @return AcsAccessGroup OK
     */
    public function get(string $acs_access_group_id): AcsAccessGroup
    {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;

        $res = Body::decode(
            $this->client->request("POST", "/acs/access_groups/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return AcsAccessGroup::from_json($res->acs_access_group);
    }

    /**
     * Returns a list of all [access groups](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all access groups.
     * @param string $acs_user_id ID of the access system user for which you want to retrieve all access groups.
     * @param string $search String for which to search. Filters returned access groups to include all records that satisfy a partial match using `name` or `acs_access_group_id`.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access groups.
     * @return array OK
     */
    public function list(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $search = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("POST", "/acs/access_groups/list", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(
            fn($r) => AcsAccessGroup::from_json($r),
            $res->acs_access_groups,
        );
    }

    /**
     * Returns a list of all accessible entrances for a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group for which you want to retrieve all accessible entrances.
     * @return array OK
     */
    public function list_accessible_entrances(
        string $acs_access_group_id,
    ): array {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/acs/access_groups/list_accessible_entrances",
                ["json" => (object) $request_payload],
            ),
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management) in an [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group for which you want to retrieve all access system users.
     * @return array OK
     */
    public function list_users(string $acs_access_group_id): array
    {
        $request_payload = [];

        $request_payload["acs_access_group_id"] = $acs_access_group_id;

        $res = Body::decode(
            $this->client->request("POST", "/acs/access_groups/list_users", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res->acs_users);
    }

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group from which you want to remove an access system user.
     * @param string $acs_user_id ID of the access system user that you want to remove from an access group.
     * @param string $user_identity_id ID of the user identity associated with the user that you want to remove from an access group.
     * @return void OK
     */
    public function remove_user(
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

        $this->client->request("POST", "/acs/access_groups/remove_user", [
            "json" => (object) $request_payload,
        ]);
    }
}
