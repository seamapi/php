<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsSystem;

class AcsSystems
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_system_id): AcsSystem
    {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->client->post("/acs/systems/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AcsSystem::from_json($json->acs_system);
    }

    public function list(?string $connected_account_id = null): array
    {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $res = $this->seam->client->post("/acs/systems/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsSystem::from_json($r),
            $json->acs_systems
        );
    }

    public function list_compatible_credential_manager_acs_systems(
        string $acs_system_id
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->client->post(
            "/acs/systems/list_compatible_credential_manager_acs_systems",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsSystem::from_json($r),
            $json->acs_systems
        );
    }
}
