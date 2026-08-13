<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\UnmanagedUserIdentity;

class UserIdentitiesUnmanagedClient
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
     * Returns a specified unmanaged [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) (where is_managed = false).
     *
     * @param string $user_identity_id ID of the unmanaged user identity that you want to get.
     * @return UnmanagedUserIdentity OK
     */
    public function get(?string $user_identity_id = null): UnmanagedUserIdentity
    {
        if ($user_identity_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /user_identities/unmanaged/get",
            );
        }
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/unmanaged/get", [
                "query" => $request_payload,
            ]),
        );

        return UnmanagedUserIdentity::from_json($res->user_identity);
    }

    /**
     * Returns a list of all unmanaged [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) (where is_managed = false).
     *
     * @param string $created_before Timestamp by which to limit returned unmanaged user identities. Returns user identities created before this timestamp.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned unmanaged user identities to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address`,  `user_identity_id` or `acs_system_id`.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $created_before = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

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

        $res = Body::decode(
            $this->client->request("GET", "/user_identities/unmanaged/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedUserIdentity::from_json($r),
            $res->user_identities,
        );
    }

    /**
     * Updates an unmanaged [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) to make it managed.
     *
     * This endpoint can only be used to convert unmanaged user identities to managed ones by setting `is_managed` to `true`. It cannot be used to convert managed user identities back to unmanaged.
     *
     * @param bool $is_managed Must be set to true to convert the unmanaged user identity to managed.
     * @param string $user_identity_id ID of the unmanaged user identity that you want to update.
     * @param string $user_identity_key Unique key for the user identity. If not provided, the existing key will be preserved.
     * @return void OK
     */
    public function update(
        ?bool $is_managed = null,
        ?string $user_identity_id = null,
        ?string $user_identity_key = null,
    ): void {
        if ($is_managed === null && $user_identity_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /user_identities/unmanaged/update",
            );
        }
        $request_payload = [];

        $request_payload["is_managed"] = $is_managed;
        $request_payload["user_identity_id"] = $user_identity_id;
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->client->request("PATCH", "/user_identities/unmanaged/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
