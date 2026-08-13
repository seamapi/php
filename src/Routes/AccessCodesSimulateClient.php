<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\UnmanagedAccessCode;

class AccessCodesSimulateClient
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
     * Simulates the creation of an [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code of the simulated unmanaged access code.
     * @param string $device_id ID of the device for which you want to simulate the creation of an unmanaged access code.
     * @param string $name Name of the simulated unmanaged access code.
     * @return UnmanagedAccessCode OK
     */
    public function create_unmanaged_access_code(
        string $code,
        string $device_id,
        string $name,
    ): UnmanagedAccessCode {
        $request_payload = [];

        $request_payload["code"] = $code;
        $request_payload["device_id"] = $device_id;
        $request_payload["name"] = $name;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/access_codes/simulate/create_unmanaged_access_code",
                ["json" => (object) $request_payload],
            ),
        );

        return UnmanagedAccessCode::from_json($res->access_code);
    }
}
