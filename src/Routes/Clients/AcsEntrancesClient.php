<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsEntrance;
use Seam\Routes\Objects\AcsCredential;

class AcsEntrancesClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $acs_entrance_id): AcsEntrance
  {
    $request_payload = [];

    if ($acs_entrance_id !== null) {
      $request_payload["acs_entrance_id"] = $acs_entrance_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/entrances/get",
      json: (object) $request_payload,
      inner_object: "acs_entrance"
    );

    return AcsEntrance::from_json($res);
  }

  public function grant_access(
    string $acs_entrance_id,
    string $acs_user_id
  ): void {
    $request_payload = [];

    if ($acs_entrance_id !== null) {
      $request_payload["acs_entrance_id"] = $acs_entrance_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/entrances/grant_access",
      json: (object) $request_payload
    );
  }

  public function list(
    string $acs_credential_id = null,
    string $acs_system_id = null
  ): array {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }
    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/entrances/list",
      json: (object) $request_payload,
      inner_object: "acs_entrances"
    );

    return array_map(fn($r) => AcsEntrance::from_json($r), $res);
  }

  public function list_credentials_with_access(
    string $acs_entrance_id,
    array $include_if = null
  ): array {
    $request_payload = [];

    if ($acs_entrance_id !== null) {
      $request_payload["acs_entrance_id"] = $acs_entrance_id;
    }
    if ($include_if !== null) {
      $request_payload["include_if"] = $include_if;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/entrances/list_credentials_with_access",
      json: (object) $request_payload,
      inner_object: "acs_credentials"
    );

    return array_map(fn($r) => AcsCredential::from_json($r), $res);
  }
}
