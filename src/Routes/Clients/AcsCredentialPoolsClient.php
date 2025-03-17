<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\AcsCredentialPool;

class AcsCredentialPoolsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function list(string $acs_system_id): array
  {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credential_pools/list",
      json: (object) $request_payload,
      inner_object: "acs_credential_pools"
    );

    return array_map(fn($r) => AcsCredentialPool::from_json($r), $res);
  }
}
