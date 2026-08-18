<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\UnmanagedAccessGrant;

class AccessGrantsUnmanagedClient
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
     * Get an unmanaged Access Grant (where is_managed = false).
     *
     * @param string $access_grant_id ID of unmanaged Access Grant to get.
     * @return UnmanagedAccessGrant OK
     */
    public function get(string $access_grant_id): UnmanagedAccessGrant
    {
        $request_payload = [];

        $request_payload["access_grant_id"] = $access_grant_id;

        $res = Body::decode(
            $this->client->request("GET", "/access_grants/unmanaged/get", [
                "query" => $request_payload,
            ]),
        );

        return UnmanagedAccessGrant::from_json($res->access_grant);
    }

    /**
     * Gets unmanaged Access Grants (where is_managed = false).
     *
     * @param string $acs_entrance_id ID of the entrance by which you want to filter the list of unmanaged Access Grants.
     * @param string $acs_system_id ID of the access system by which you want to filter the list of unmanaged Access Grants.
     * @param float $limit Numerical limit on the number of unmanaged access grants to return.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $reservation_key Filter unmanaged Access Grants by reservation_key.
     * @param string $user_identity_id ID of user identity by which you want to filter the list of unmanaged Access Grants.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?float $limit = null,
        string|NullValue|null $page_cursor = null,
        ?string $reservation_key = null,
        ?string $user_identity_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/access_grants/unmanaged/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedAccessGrant::from_json($r),
            $res->access_grants,
        );
    }

    /**
     * Updates an unmanaged Access Grant to make it managed.
     *
     * This endpoint can only be used to convert unmanaged access grants to managed ones by setting `is_managed` to `true`. It cannot be used to convert managed access grants back to unmanaged.
     *
     * When converting an unmanaged access grant to managed, all associated access methods will also be converted to managed.
     *
     * @param string $access_grant_id ID of the unmanaged Access Grant to update.
     * @param true $is_managed Must be set to true to convert the unmanaged access grant to managed.
     * @param string $access_grant_key Unique key for the access grant. If not provided, the existing key will be preserved.
     * @return void OK
     */
    public function update(
        string $access_grant_id,
        true $is_managed,
        ?string $access_grant_key = null,
    ): void {
        $request_payload = [];

        $request_payload["access_grant_id"] = $access_grant_id;
        $request_payload["is_managed"] = $is_managed;
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }

        $this->client->request("PATCH", "/access_grants/unmanaged/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
