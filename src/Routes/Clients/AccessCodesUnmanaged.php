<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\UnmanagedAccessCode;

class AccessCodesUnmanaged
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function convert_to_managed(
        string $access_code_id,
        ?bool $is_external_modification_allowed = null,
        ?bool $allow_external_modification = null,
        ?bool $force = null,
        ?bool $sync = null
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($force !== null) {
            $request_payload["force"] = $force;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->client->post(
            "/access_codes/unmanaged/convert_to_managed",
            ["json" => (object) $request_payload]
        );
    }

    public function delete(string $access_code_id, ?bool $sync = null): void
    {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->client->post("/access_codes/unmanaged/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(
        ?string $device_id = null,
        ?string $access_code_id = null,
        ?string $code = null
    ): UnmanagedAccessCode {
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

        $res = $this->seam->client->post("/access_codes/unmanaged/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return UnmanagedAccessCode::from_json($json->access_code);
    }

    public function list(
        string $device_id,
        ?string $user_identifier_key = null
    ): array {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->client->post("/access_codes/unmanaged/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => UnmanagedAccessCode::from_json($r),
            $json->access_codes
        );
    }

    public function update(
        string $access_code_id,
        bool $is_managed,
        ?bool $allow_external_modification = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $force = null
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
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
        if ($force !== null) {
            $request_payload["force"] = $force;
        }

        $this->seam->client->post("/access_codes/unmanaged/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
