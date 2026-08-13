<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\Phone;

class PhonesClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public PhonesSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new PhonesSimulateClient($client, $defaults);
    }

    /**
     * Deactivates a phone, which is useful, for example, if a user has lost their phone. For more information, see [App User Lost Phone Process](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity#app-user-lost-phone-process).
     *
     * @param string $device_id Device ID of the phone that you want to deactivate.
     * @return void OK
     */
    public function deactivate(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request("DELETE", "/phones/deactivate", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Returns a specified [phone](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity).
     *
     * @param string $device_id Device ID of the phone that you want to get.
     * @return Phone OK
     */
    public function get(string $device_id): Phone
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request("GET", "/phones/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return Phone::from_json($res->phone);
    }

    /**
     * Returns a list of all [phones](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity). To filter the list of returned phones by a specific owner user identity or credential, include the `owner_user_identity_id` or `acs_credential_id`, respectively, in the request body.
     *
     * @param string $acs_credential_id ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) by which you want to filter the list of returned phones.
     * @param string $owner_user_identity_id ID of the user identity that represents the owner by which you want to filter the list of returned phones.
     * @return array OK
     */
    public function list(
        ?string $acs_credential_id = null,
        ?string $owner_user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($owner_user_identity_id !== null) {
            $request_payload[
                "owner_user_identity_id"
            ] = $owner_user_identity_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/phones/list", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(fn($r) => Phone::from_json($r), $res->phones);
    }
}
