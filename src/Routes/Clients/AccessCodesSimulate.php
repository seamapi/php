<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\UnmanagedAccessCode;

class AccessCodesSimulate
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create_unmanaged_access_code(
        string $device_id,
        string $name,
        string $code
    ): UnmanagedAccessCode {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }

        $res = $this->seam->client->post(
            "/access_codes/simulate/create_unmanaged_access_code",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return UnmanagedAccessCode::from_json($json->access_code);
    }
}
