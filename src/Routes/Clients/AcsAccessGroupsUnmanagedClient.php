<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\UnmanagedAcsAccessGroup;

class AcsAccessGroupsUnmanagedClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $acs_access_group_id): UnmanagedAcsAccessGroup
  {
    $request_payload = [];

    if ($acs_access_group_id !== null) {
      $request_payload["acs_access_group_id"] = $acs_access_group_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/access_groups/unmanaged/get",
      json: (object) $request_payload,
      inner_object: "acs_access_group"
    );

    return UnmanagedAcsAccessGroup::from_json($res);
  }

  public function list(
    string $acs_system_id = null,
    string $acs_user_id = null
  ): array {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/access_groups/unmanaged/list",
      json: (object) $request_payload,
      inner_object: "acs_access_groups"
    );

    return array_map(
      fn($r) => UnmanagedAcsAccessGroup::from_json($r),
      $res
    );
  }
}
