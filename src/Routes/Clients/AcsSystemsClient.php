<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\AcsSystem;

class AcsSystemsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $acs_system_id): AcsSystem
  {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/systems/get",
      json: (object) $request_payload,
      inner_object: "acs_system"
    );

    return AcsSystem::from_json($res);
  }

  public function list(string $connected_account_id = null): array
  {
    $request_payload = [];

    if ($connected_account_id !== null) {
      $request_payload["connected_account_id"] = $connected_account_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/systems/list",
      json: (object) $request_payload,
      inner_object: "acs_systems"
    );

    return array_map(fn($r) => AcsSystem::from_json($r), $res);
  }

  public function list_compatible_credential_manager_acs_systems(
    string $acs_system_id
  ): array {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/systems/list_compatible_credential_manager_acs_systems",
      json: (object) $request_payload,
      inner_object: "acs_systems"
    );

    return array_map(fn($r) => AcsSystem::from_json($r), $res);
  }
}
