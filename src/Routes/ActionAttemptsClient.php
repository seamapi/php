<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Http\ResolveActionAttempt;
use Seam\NullValue;
use Seam\Resources\ActionAttempt;

class ActionAttemptsClient
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
     * Returns a specified [action attempt](https://docs.seam.co/core-concepts/action-attempts).
     *
     * @param string $action_attempt_id ID of the action attempt that you want to get.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function get(
        string $action_attempt_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["action_attempt_id"] = $action_attempt_id;

        $res = Body::decode(
            $this->client->request("GET", "/action_attempts/get", [
                "query" => $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Returns a list of the [action attempts](https://docs.seam.co/core-concepts/action-attempts) that you specify as an array of `action_attempt_id`s.
     *
     * @param array $action_attempt_ids IDs of the action attempts that you want to retrieve.
     * @param string $device_id ID of the device to filter action attempts by.
     * @param int $limit Maximum number of records to return per page.
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?array $action_attempt_ids = null,
        ?string $device_id = null,
        ?int $limit = null,
        string|NullValue|null $page_cursor = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($action_attempt_ids !== null) {
            $request_payload["action_attempt_ids"] = $action_attempt_ids;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }

        $res = Body::decode(
            $this->client->request("GET", "/action_attempts/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ActionAttempt::from_json($r),
            $res->action_attempts,
        );
    }
}
