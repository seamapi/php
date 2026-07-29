<?php

namespace Seam\Routes;

use Seam\Resources\UnmanagedAccessCode;
use Seam\SeamClient;

class AccessCodesSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Simulates the creation of an [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code of the simulated unmanaged access code.
     * @param string $device_id ID of the device for which you want to simulate the creation of an unmanaged access code.
     * @param string $name Name of the simulated unmanaged access code.
     * @return UnmanagedAccessCode OK
     */
    public function create_unmanaged_access_code(
        string $code,
        string $device_id,
        string $name,
    ): UnmanagedAccessCode {
        $request_payload = [];

        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/simulate/create_unmanaged_access_code",
            json: (object) $request_payload,
        );

        return UnmanagedAccessCode::from_json($res->access_code);
    }
}
