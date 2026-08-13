<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\AcsSystem;

class AcsSystemsClient
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
     * Returns a specified [access system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * @param string $acs_system_id ID of the access system that you want to get.
     * @return AcsSystem OK
     */
    public function get(?string $acs_system_id = null): AcsSystem
    {
        if ($acs_system_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/systems/get",
            );
        }
        $request_payload = [];

        $request_payload["acs_system_id"] = $acs_system_id;

        $res = Body::decode(
            $this->client->request("GET", "/acs/systems/get", [
                "query" => $request_payload,
            ]),
        );

        return AcsSystem::from_json($res->acs_system);
    }

    /**
     * Returns a list of all [access systems](https://docs.seam.co/low-level-apis/access-systems).
     *
     * To filter the list of returned access systems by a specific connected account ID, include the `connected_account_id` in the request body. If you omit the `connected_account_id` parameter, the response includes all access systems connected to your workspace.
     *
     * @param string $connected_account_id ID of the connected account by which you want to filter the list of access systems.
     * @param string $customer_key Customer key for which you want to list access systems.
     * @param string $search String for which to search. Filters returned access systems to include all records that satisfy a partial match using `name` or `acs_system_id`.
     * @return array OK
     */
    public function list(
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $search = null,
    ): array {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }

        $res = Body::decode(
            $this->client->request("GET", "/acs/systems/list", [
                "query" => $request_payload,
            ]),
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }

    /**
     * Returns a list of all credential manager systems that are compatible with a specified [access system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * Specify the access system for which you want to retrieve all compatible credential manager systems by including the corresponding `acs_system_id` in the request body.
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all compatible credential manager systems.
     * @return array OK
     */
    public function list_compatible_credential_manager_acs_systems(
        ?string $acs_system_id = null,
    ): array {
        if ($acs_system_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/systems/list_compatible_credential_manager_acs_systems",
            );
        }
        $request_payload = [];

        $request_payload["acs_system_id"] = $acs_system_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/acs/systems/list_compatible_credential_manager_acs_systems",
                ["query" => $request_payload],
            ),
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }

    /**
     * Reports ACS system device status including encoders and entrances.
     *
     * @param string $acs_system_id ID of the ACS system to report resources for
     * @param array $acs_encoders Array of ACS encoders to report
     * @param array $acs_entrances Array of ACS entrances to report
     * @return void OK
     */
    public function report_devices(
        ?string $acs_system_id = null,
        ?array $acs_encoders = null,
        ?array $acs_entrances = null,
    ): void {
        if ($acs_system_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/systems/report_devices",
            );
        }
        $request_payload = [];

        $request_payload["acs_system_id"] = $acs_system_id;
        if ($acs_encoders !== null) {
            $request_payload["acs_encoders"] = $acs_encoders;
        }
        if ($acs_entrances !== null) {
            $request_payload["acs_entrances"] = $acs_entrances;
        }

        $this->client->request("POST", "/acs/systems/report_devices", [
            "json" => (object) $request_payload,
        ]);
    }
}
