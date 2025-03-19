<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsAccessGroup;
use Seam\Routes\Objects\AcsEntrance;
use Seam\Routes\Objects\AcsUser;

class AcsAccessGroups
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function add_user(
        string $acs_access_group_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/access_groups/add_user", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $acs_access_group_id): AcsAccessGroup
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->client->post("/acs/access_groups/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AcsAccessGroup::from_json($json->acs_access_group);
    }

    public function list(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->client->post("/acs/access_groups/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsAccessGroup::from_json($r),
            $json->acs_access_groups
        );
    }

    public function list_accessible_entrances(
        string $acs_access_group_id
    ): array {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->client->post(
            "/acs/access_groups/list_accessible_entrances",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $json->acs_entrances
        );
    }

    public function list_users(string $acs_access_group_id): array
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->client->post("/acs/access_groups/list_users", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => AcsUser::from_json($r), $json->acs_users);
    }

    public function remove_user(
        string $acs_access_group_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/access_groups/remove_user", [
            "json" => (object) $request_payload,
        ]);
    }
}
