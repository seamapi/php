<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\InstantKey;

class InstantKeysClient
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
     * Deletes a specified [Instant Key](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $instant_key_id ID of the Instant Key that you want to delete.
     * @return void OK
     */
    public function delete(string $instant_key_id): void
    {
        $request_payload = [];

        $request_payload["instant_key_id"] = $instant_key_id;

        $this->client->request("DELETE", "/instant_keys/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Gets an [instant key](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $instant_key_id ID of the instant key to get.
     * @param string $instant_key_url URL of the instant key to get.
     * @return InstantKey OK
     */
    public function get(
        ?string $instant_key_id = null,
        ?string $instant_key_url = null,
    ): InstantKey {
        $request_payload = [];

        if ($instant_key_id !== null) {
            $request_payload["instant_key_id"] = $instant_key_id;
        }
        if ($instant_key_url !== null) {
            $request_payload["instant_key_url"] = $instant_key_url;
        }

        $res = Body::decode(
            $this->client->request("GET", "/instant_keys/get", [
                "query" => $request_payload,
            ]),
        );

        return InstantKey::from_json($res->instant_key);
    }

    /**
     * Returns a list of all [instant keys](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $user_identity_id ID of the user identity by which you want to filter the list of Instant Keys.
     * @return array OK
     */
    public function list(?string $user_identity_id = null): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/instant_keys/list", [
                "query" => $request_payload,
            ]),
        );

        return array_map(
            fn($r) => InstantKey::from_json($r),
            $res->instant_keys,
        );
    }
}
