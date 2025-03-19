<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsUser;
use Seam\Routes\Objects\AcsEntrance;

class AcsUsers
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function add_to_access_group(
        string $acs_user_id,
        string $acs_access_group_id
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $this->seam->client->post("/acs/users/add_to_access_group", [
            "json" => (object) $request_payload,
        ]);
    }

    public function create(
        string $full_name,
        string $acs_system_id,
        ?array $acs_access_group_ids = null,
        ?string $user_identity_id = null,
        mixed $access_schedule = null,
        ?string $email = null,
        ?string $phone_number = null,
        ?string $email_address = null
    ): AcsUser {
        $request_payload = [];

        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_access_group_ids !== null) {
            $request_payload["acs_access_group_ids"] = $acs_access_group_ids;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }

        $res = $this->seam->client->post("/acs/users/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AcsUser::from_json($json->acs_user);
    }

    public function delete(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/users/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $acs_user_id): AcsUser
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->client->post("/acs/users/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AcsUser::from_json($json->acs_user);
    }

    public function list(
        ?string $user_identity_id = null,
        ?string $user_identity_phone_number = null,
        ?string $user_identity_email_address = null,
        ?string $acs_system_id = null,
        ?string $search = null,
        mixed $limit = null,
        ?string $created_before = null,
        ?string $page_cursor = null
    ): array {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_phone_number !== null) {
            $request_payload[
                "user_identity_phone_number"
            ] = $user_identity_phone_number;
        }
        if ($user_identity_email_address !== null) {
            $request_payload[
                "user_identity_email_address"
            ] = $user_identity_email_address;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }

        $res = $this->seam->client->post("/acs/users/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => AcsUser::from_json($r), $json->acs_users);
    }

    public function list_accessible_entrances(string $acs_user_id): array
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->client->post(
            "/acs/users/list_accessible_entrances",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $json->acs_entrances
        );
    }

    public function remove_from_access_group(
        string $acs_user_id,
        string $acs_access_group_id
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $this->seam->client->post("/acs/users/remove_from_access_group", [
            "json" => (object) $request_payload,
        ]);
    }

    public function revoke_access_to_all_entrances(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/users/revoke_access_to_all_entrances", [
            "json" => (object) $request_payload,
        ]);
    }

    public function suspend(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/users/suspend", [
            "json" => (object) $request_payload,
        ]);
    }

    public function unsuspend(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/acs/users/unsuspend", [
            "json" => (object) $request_payload,
        ]);
    }

    public function update(
        string $acs_user_id,
        mixed $access_schedule = null,
        ?string $full_name = null,
        ?string $email = null,
        ?string $phone_number = null,
        ?string $email_address = null,
        ?string $hid_acs_system_id = null
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($hid_acs_system_id !== null) {
            $request_payload["hid_acs_system_id"] = $hid_acs_system_id;
        }

        $this->seam->client->post("/acs/users/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
