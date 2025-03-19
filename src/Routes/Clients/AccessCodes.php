<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AccessCode;

class AccessCodes
{
    private Seam $seam;
    public AccessCodesSimulate $simulate;
    public AccessCodesUnmanaged $unmanaged;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->simulate = new AccessCodesSimulate($seam);
        $this->unmanaged = new AccessCodesUnmanaged($seam);
    }

    public function create(
        string $device_id,
        ?string $name = null,
        ?string $starts_at = null,
        ?string $ends_at = null,
        ?string $code = null,
        ?bool $sync = null,
        ?bool $attempt_for_offline_device = null,
        ?string $common_code_key = null,
        ?bool $prefer_native_scheduling = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $allow_external_modification = null,
        ?bool $is_external_modification_allowed = null,
        ?float $preferred_code_length = null,
        ?bool $use_offline_access_code = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null
    ): AccessCode {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($common_code_key !== null) {
            $request_payload["common_code_key"] = $common_code_key;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }

        $res = $this->seam->client->post("/access_codes/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AccessCode::from_json($json->access_code);
    }

    public function create_multiple(
        array $device_ids,
        ?string $behavior_when_code_cannot_be_shared = null,
        ?float $preferred_code_length = null,
        ?string $name = null,
        ?string $starts_at = null,
        ?string $ends_at = null,
        ?string $code = null,
        ?bool $attempt_for_offline_device = null,
        ?bool $prefer_native_scheduling = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $allow_external_modification = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $use_offline_access_code = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null
    ): array {
        $request_payload = [];

        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($behavior_when_code_cannot_be_shared !== null) {
            $request_payload[
                "behavior_when_code_cannot_be_shared"
            ] = $behavior_when_code_cannot_be_shared;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }

        $res = $this->seam->client->post("/access_codes/create_multiple", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AccessCode::from_json($r),
            $json->access_codes
        );
    }

    public function delete(
        string $access_code_id,
        ?string $device_id = null,
        ?bool $sync = null
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->client->post("/access_codes/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function generate_code(string $device_id): AccessCode
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->client->post("/access_codes/generate_code", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AccessCode::from_json($json->generated_code);
    }

    public function get(
        ?string $device_id = null,
        ?string $access_code_id = null,
        ?string $code = null
    ): AccessCode {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }

        $res = $this->seam->client->post("/access_codes/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return AccessCode::from_json($json->access_code);
    }

    public function list(
        ?string $device_id = null,
        ?array $access_code_ids = null,
        ?string $user_identifier_key = null
    ): array {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->client->post("/access_codes/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AccessCode::from_json($r),
            $json->access_codes
        );
    }

    public function pull_backup_access_code(string $access_code_id): AccessCode
    {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }

        $res = $this->seam->client->post(
            "/access_codes/pull_backup_access_code",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return AccessCode::from_json($json->access_code);
    }

    public function update(
        string $access_code_id,
        ?string $name = null,
        ?string $starts_at = null,
        ?string $ends_at = null,
        ?string $code = null,
        ?bool $sync = null,
        ?bool $attempt_for_offline_device = null,
        ?bool $prefer_native_scheduling = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $allow_external_modification = null,
        ?bool $is_external_modification_allowed = null,
        ?float $preferred_code_length = null,
        ?bool $use_offline_access_code = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null,
        ?string $device_id = null,
        ?string $type = null,
        ?bool $is_managed = null
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($type !== null) {
            $request_payload["type"] = $type;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }

        $this->seam->client->post("/access_codes/update", [
            "json" => (object) $request_payload,
        ]);
    }

    public function update_multiple(
        string $common_code_key,
        ?string $ends_at = null,
        ?string $starts_at = null,
        ?string $name = null
    ): void {
        $request_payload = [];

        if ($common_code_key !== null) {
            $request_payload["common_code_key"] = $common_code_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $this->seam->client->post("/access_codes/update_multiple", [
            "json" => (object) $request_payload,
        ]);
    }
}
