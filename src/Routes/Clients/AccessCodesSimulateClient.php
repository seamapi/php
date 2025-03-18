<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\UnmanagedAccessCode;

class AccessCodesSimulateClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create_unmanaged_access_code(
    string $code,
    string $device_id,
    string $name
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
      inner_object: "access_code"
    );

    return UnmanagedAccessCode::from_json($res);
  }
}
