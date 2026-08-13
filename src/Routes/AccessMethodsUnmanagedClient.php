<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\UnmanagedAccessMethod;

class AccessMethodsUnmanagedClient
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
     * Gets an unmanaged access method (where is_managed = false).
     *
     * @param string $access_method_id ID of unmanaged access method to get.
     * @return UnmanagedAccessMethod OK
     */
    public function get(string $access_method_id): UnmanagedAccessMethod
    {
        $request_payload = [];

        $request_payload["access_method_id"] = $access_method_id;

        $res = Body::decode(
            $this->client->request("GET", "/access_methods/unmanaged/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return UnmanagedAccessMethod::from_json($res->access_method);
    }

    /**
     * Lists all unmanaged access methods (where is_managed = false), usually filtered by Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to list unmanaged access methods for.
     * @param string $acs_entrance_id ID of the entrance for which you want to retrieve all unmanaged access methods.
     * @param string $device_id ID of the device for which you want to retrieve all unmanaged access methods.
     * @param string $space_id ID of the space for which you want to retrieve all unmanaged access methods.
     * @return array OK
     */
    public function list(
        string $access_grant_id,
        ?string $acs_entrance_id = null,
        ?string $device_id = null,
        ?string $space_id = null,
    ): array {
        $request_payload = [];

        $request_payload["access_grant_id"] = $access_grant_id;
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $res = Body::decode(
            $this->client->request("GET", "/access_methods/unmanaged/list", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(
            fn($r) => UnmanagedAccessMethod::from_json($r),
            $res->access_methods,
        );
    }
}
