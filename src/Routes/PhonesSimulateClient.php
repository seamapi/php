<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\Phone;

class PhonesSimulateClient
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
     * Creates a new simulated phone in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Creating a Simulated Phone for a User Identity](https://docs.seam.co/capability-guides/mobile-access/developing-in-a-sandbox-workspace#creating-a-simulated-phone-for-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to associate with the simulated phone.
     * @param mixed $assa_abloy_metadata ASSA ABLOY metadata that you want to associate with the simulated phone.
     * @param string $custom_sdk_installation_id ID of the custom SDK installation that you want to use for the simulated phone.
     * @param mixed $phone_metadata Metadata that you want to associate with the simulated phone.
     * @return Phone OK
     */
    public function create_sandbox_phone(
        ?string $user_identity_id = null,
        mixed $assa_abloy_metadata = null,
        ?string $custom_sdk_installation_id = null,
        mixed $phone_metadata = null,
    ): Phone {
        if ($user_identity_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /phones/simulate/create_sandbox_phone",
            );
        }
        $request_payload = [];

        $request_payload["user_identity_id"] = $user_identity_id;
        if ($assa_abloy_metadata !== null) {
            $request_payload["assa_abloy_metadata"] = $assa_abloy_metadata;
        }
        if ($custom_sdk_installation_id !== null) {
            $request_payload[
                "custom_sdk_installation_id"
            ] = $custom_sdk_installation_id;
        }
        if ($phone_metadata !== null) {
            $request_payload["phone_metadata"] = $phone_metadata;
        }

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/phones/simulate/create_sandbox_phone",
                ["json" => (object) $request_payload],
            ),
        );

        return Phone::from_json($res->phone);
    }
}
