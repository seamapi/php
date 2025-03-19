<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\UserIdentity;
use Seam\Routes\Objects\Device;
use Seam\Routes\Objects\AcsSystem;
use Seam\Routes\Objects\AcsUser;

class UserIdentities
{
    private Seam $seam;
    public UserIdentitiesEnrollmentAutomations $enrollment_automations;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->enrollment_automations = new UserIdentitiesEnrollmentAutomations(
            $seam
        );
    }

    public function add_acs_user(
        string $user_identity_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/user_identities/add_acs_user", [
            "json" => (object) $request_payload,
        ]);
    }

    public function create(
        ?string $user_identity_key = null,
        ?string $email_address = null,
        ?string $phone_number = null,
        ?string $full_name = null
    ): UserIdentity {
        $request_payload = [];

        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }

        $res = $this->seam->client->post("/user_identities/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return UserIdentity::from_json($json->user_identity);
    }

    public function delete(string $user_identity_id): void
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->client->post("/user_identities/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(
        ?string $user_identity_id = null,
        ?string $user_identity_key = null
    ): UserIdentity {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $res = $this->seam->client->post("/user_identities/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return UserIdentity::from_json($json->user_identity);
    }

    public function grant_access_to_device(
        string $user_identity_id,
        string $device_id
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->client->post("/user_identities/grant_access_to_device", [
            "json" => (object) $request_payload,
        ]);
    }

    public function list(
        ?string $credential_manager_acs_system_id = null
    ): array {
        $request_payload = [];

        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }

        $res = $this->seam->client->post("/user_identities/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => UserIdentity::from_json($r),
            $json->user_identities
        );
    }

    public function list_accessible_devices(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->client->post(
            "/user_identities/list_accessible_devices",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Device::from_json($r), $json->devices);
    }

    public function list_acs_systems(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->client->post("/user_identities/list_acs_systems", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsSystem::from_json($r),
            $json->acs_systems
        );
    }

    public function list_acs_users(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->client->post("/user_identities/list_acs_users", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => AcsUser::from_json($r), $json->acs_users);
    }

    public function remove_acs_user(
        string $user_identity_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->client->post("/user_identities/remove_acs_user", [
            "json" => (object) $request_payload,
        ]);
    }

    public function revoke_access_to_device(
        string $user_identity_id,
        string $device_id
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->client->post("/user_identities/revoke_access_to_device", [
            "json" => (object) $request_payload,
        ]);
    }

    public function update(
        string $user_identity_id,
        ?string $user_identity_key = null,
        ?string $email_address = null,
        ?string $phone_number = null,
        ?string $full_name = null
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }

        $this->seam->client->post("/user_identities/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
