<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Phone;

class PhonesSimulateClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create_sandbox_phone(
    string $user_identity_id,
    mixed $assa_abloy_metadata = null,
    string $custom_sdk_installation_id = null,
    mixed $phone_metadata = null
  ): Phone {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($assa_abloy_metadata !== null) {
      $request_payload["assa_abloy_metadata"] = $assa_abloy_metadata;
    }
    if ($custom_sdk_installation_id !== null) {
      $request_payload["custom_sdk_installation_id"] = $custom_sdk_installation_id;
    }
    if ($phone_metadata !== null) {
      $request_payload["phone_metadata"] = $phone_metadata;
    }

    $res = $this->seam->request(
      "POST",
      "/phones/simulate/create_sandbox_phone",
      json: (object) $request_payload,
      inner_object: "phone"
    );

    return Phone::from_json($res);
  }
}
