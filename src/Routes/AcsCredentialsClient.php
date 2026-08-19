<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\AcsCredential;
use Seam\Resources\AcsEntrance;

class AcsCredentialsClient
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
     * Assigns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) to a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_credential_id ID of the credential that you want to assign to an access system user.
     * @param string $acs_user_id ID of the access system user to whom you want to assign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity to whom you want to assign a credential. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the credential belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
    public function assign(
        string $acs_credential_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("PATCH", "/acs/credentials/assign", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Creates a new [credential](https://docs.seam.co/low-level-apis/managing-credentials) for a specified [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management). For granting access, we recommend [Access Grants](https://docs.seam.co/use-cases/granting-access) instead: they create and manage the underlying credentials for you, across access systems and standalone smart locks alike. Use this low-level endpoint only when you need direct control over an individual ACS credential.
     *
     * @param string $access_method Access method for the new credential. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
     * @param string $acs_system_id ID of the access system to which the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`.
     * @param string $acs_user_id ID of the access system user to whom the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`.
     * @param list<string> $allowed_acs_entrance_ids Set of IDs of the [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for which the new credential grants access.
     * @param mixed $assa_abloy_vostio_metadata Vostio-specific metadata for the new credential.
     * @param string $code Access (PIN) code for the new credential. There may be manufacturer-specific code restrictions. For details, see the applicable [device or system integration guide](https://docs.seam.co/device-and-system-integration-guides).
     * @param string $credential_manager_acs_system_id ACS system ID of the credential manager for the new credential.
     * @param string $ends_at Date and time at which the validity of the new credential ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param bool $is_multi_phone_sync_credential Indicates whether the new credential is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
     * @param mixed $salto_space_metadata Salto Space-specific metadata for the new credential.
     * @param string $starts_at Date and time at which the validity of the new credential starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $user_identity_id ID of the user identity to whom the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`. If the access system contains a user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the credential belongs to the access system user. If the access system does not have a corresponding user, one is created.
     * @param mixed $visionline_metadata Visionline-specific metadata for the new credential.
     * @return AcsCredential OK
     */
    public function create(
        string $access_method,
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?array $allowed_acs_entrance_ids = null,
        mixed $assa_abloy_vostio_metadata = null,
        ?string $code = null,
        ?string $credential_manager_acs_system_id = null,
        ?string $ends_at = null,
        ?bool $is_multi_phone_sync_credential = null,
        mixed $salto_space_metadata = null,
        ?string $starts_at = null,
        ?string $user_identity_id = null,
        mixed $visionline_metadata = null,
    ): AcsCredential {
        $request_payload = [];

        $request_payload["access_method"] = $access_method;
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($allowed_acs_entrance_ids !== null) {
            $request_payload[
                "allowed_acs_entrance_ids"
            ] = $allowed_acs_entrance_ids;
        }
        if ($assa_abloy_vostio_metadata !== null) {
            $request_payload[
                "assa_abloy_vostio_metadata"
            ] = $assa_abloy_vostio_metadata;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
        }
        if ($salto_space_metadata !== null) {
            $request_payload["salto_space_metadata"] = $salto_space_metadata;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($visionline_metadata !== null) {
            $request_payload["visionline_metadata"] = $visionline_metadata;
        }

        $res = Body::decode(
            $this->client->request("POST", "/acs/credentials/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return AcsCredential::from_json(
            Body::read($res, "acs_credential", "/acs/credentials/create"),
        );
    }

    /**
     * Deletes a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to delete.
     * @return void OK
     */
    public function delete(string $acs_credential_id): void
    {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;

        $this->client->request("DELETE", "/acs/credentials/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Returns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to get.
     * @return AcsCredential OK
     */
    public function get(string $acs_credential_id): AcsCredential
    {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;

        $res = Body::decode(
            $this->client->request("GET", "/acs/credentials/get", [
                "query" => $request_payload,
            ]),
        );

        return AcsCredential::from_json(
            Body::read($res, "acs_credential", "/acs/credentials/get"),
        );
    }

    /**
     * Returns a list of all [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_user_id ID of the access system user for which you want to retrieve all credentials.
     * @param string $acs_system_id ID of the access system for which you want to retrieve all credentials.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all credentials.
     * @param string $created_before Date and time, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format, before which events to return were created.
     * @param bool $is_multi_phone_sync_credential Indicates whether you want to retrieve only multi-phone sync credentials or non-multi-phone sync credentials.
     * @param float $limit Number of credentials to return.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned credentials to include all records that satisfy a partial match using `display_name`, `code`, `card_number`, `acs_user_id` or `acs_credential_id`.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $acs_user_id = null,
        ?string $acs_system_id = null,
        ?string $user_identity_id = null,
        ?string $created_before = null,
        ?bool $is_multi_phone_sync_credential = null,
        ?float $limit = null,
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?callable $on_response = null,
    ): array {
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
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
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

        $res = Body::decode(
            $this->client->request("GET", "/acs/credentials/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsCredential::from_json($r),
            Body::read_list($res, "acs_credentials", "/acs/credentials/list"),
        );
    }

    /**
     * Returns a list of all [entrances](https://docs.seam.co/api/acs/entrances) to which a [credential](https://docs.seam.co/api/acs/credentials) grants access.
     *
     * @param string $acs_credential_id ID of the credential for which you want to retrieve all entrances to which the credential grants access.
     * @return array OK
     */
    public function list_accessible_entrances(string $acs_credential_id): array
    {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/acs/credentials/list_accessible_entrances",
                ["query" => $request_payload],
            ),
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            Body::read_list(
                $res,
                "acs_entrances",
                "/acs/credentials/list_accessible_entrances",
            ),
        );
    }

    /**
     * Unassigns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) from a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_credential_id ID of the credential that you want to unassign from an access system user.
     * @param string $acs_user_id ID of the access system user from which you want to unassign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity from which you want to unassign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @return void OK
     */
    public function unassign(
        string $acs_credential_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->client->request("PATCH", "/acs/credentials/unassign", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates the code and ends at date and time for a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to update.
     * @param string $code Replacement access (PIN) code for the credential that you want to update.
     * @param string $ends_at Replacement date and time at which the validity of the credential ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after the `starts_at` value that you set when creating the credential.
     * @return void OK
     */
    public function update(
        string $acs_credential_id,
        ?string $code = null,
        ?string $ends_at = null,
    ): void {
        $request_payload = [];

        $request_payload["acs_credential_id"] = $acs_credential_id;
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }

        $this->client->request("PATCH", "/acs/credentials/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
