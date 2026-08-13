<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Http\ResolveActionAttempt;
use Seam\Resources\AcsCredential;
use Seam\Resources\AcsEntrance;
use Seam\Resources\ActionAttempt;

class AcsEntrancesClient
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
     * Returns a specified [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance that you want to get.
     * @return AcsEntrance OK
     */
    public function get(string $acs_entrance_id): AcsEntrance
    {
        $request_payload = [];

        $request_payload["acs_entrance_id"] = $acs_entrance_id;

        $res = Body::decode(
            $this->client->request("GET", "/acs/entrances/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return AcsEntrance::from_json($res->acs_entrance);
    }

    /**
     * Grants a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) access to a specified [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance to which you want to grant an access system user access.
     * @param string $acs_user_id ID of the access system user to whom you want to grant access to an entrance. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity to whom you want to grant access to an entrance. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the access group membership belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
    public function grant_access(
        string $acs_entrance_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_entrance_id"] = $acs_entrance_id;
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("POST", "/acs/entrances/grant_access", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Returns a list of all [access system entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $access_method_id ID of the access method for which you want to retrieve all entrances to which it grants access.
     * @param string $acs_credential_id ID of the credential for which you want to retrieve all entrances.
     * @param array $acs_entrance_ids IDs of the entrances for which you want to retrieve all entrances.
     * @param string $acs_system_id ID of the access system for which you want to retrieve all entrances.
     * @param string $connected_account_id ID of the connected account for which you want to retrieve all entrances.
     * @param string $customer_key Customer key for which you want to list entrances.
     * @param int $limit Maximum number of records to return per page.
     * @param string $location_id
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned entrances to include all records that satisfy a partial match using `display_name`.
     * @param string $space_id ID of the space for which you want to list entrances.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $access_method_id = null,
        ?string $acs_credential_id = null,
        ?array $acs_entrance_ids = null,
        ?string $acs_system_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?int $limit = null,
        ?string $location_id = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
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
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $res = Body::decode(
            $this->client->request("POST", "/acs/entrances/list", [
                "json" => (object) $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    /**
     * Returns a list of all [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) with access to a specified [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance for which you want to list all credentials that grant access.
     * @param array $include_if Conditions that credentials must meet to be included in the returned list.
     * @return array OK
     */
    public function list_credentials_with_access(
        string $acs_entrance_id,
        ?array $include_if = null,
    ): array {
        $request_payload = [];

        $request_payload["acs_entrance_id"] = $acs_entrance_id;
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/acs/entrances/list_credentials_with_access",
                ["json" => (object) $request_payload],
            ),
        );

        return array_map(
            fn($r) => AcsCredential::from_json($r),
            $res->acs_credentials,
        );
    }

    /**
     * Remotely unlocks a specified [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) using a cloud_key credential. Returns an action attempt that tracks the progress of the unlock operation.
     *
     * @param string $acs_credential_id ID of the cloud_key credential to use for the unlock operation.
     * @param string $acs_entrance_id ID of the entrance to unlock.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function unlock(
        string $acs_credential_id,
        string $acs_entrance_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;
        $request_payload["acs_entrance_id"] = $acs_entrance_id;

        $res = Body::decode(
            $this->client->request("POST", "/acs/entrances/unlock", [
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
