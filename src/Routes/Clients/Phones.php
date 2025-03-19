<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Phone;

class Phones
{
    private Seam $seam;
    public PhonesSimulate $simulate;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->simulate = new PhonesSimulate($seam);
    }

    public function deactivate(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->client->post("/phones/deactivate", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $device_id): Phone
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->client->post("/phones/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Phone::from_json($json->phone);
    }

    public function list(
        ?string $owner_user_identity_id = null,
        ?string $acs_credential_id = null
    ): array {
        $request_payload = [];

        if ($owner_user_identity_id !== null) {
            $request_payload[
                "owner_user_identity_id"
            ] = $owner_user_identity_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->client->post("/phones/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Phone::from_json($r), $json->phones);
    }
}
