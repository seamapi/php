<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Phone;

class PhonesClient
{
  private Seam $seam;
  public PhonesSimulateClient $simulate;
  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
    $this->simulate = new PhonesSimulateClient($seam);
  }

  public function deactivate(string $device_id): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/phones/deactivate",
      json: (object) $request_payload
    );
  }

  public function get(string $device_id): Phone
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $res = $this->seam->request(
      "POST",
      "/phones/get",
      json: (object) $request_payload,
      inner_object: "phone"
    );

    return Phone::from_json($res);
  }

  public function list(
    string $acs_credential_id = null,
    string $owner_user_identity_id = null
  ): array {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }
    if ($owner_user_identity_id !== null) {
      $request_payload["owner_user_identity_id"] = $owner_user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/phones/list",
      json: (object) $request_payload,
      inner_object: "phones"
    );

    return array_map(fn($r) => Phone::from_json($r), $res);
  }
}
