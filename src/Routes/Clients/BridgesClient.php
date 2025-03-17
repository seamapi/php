<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;

class BridgesClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $bridge_id): void
  {
    $request_payload = [];

    if ($bridge_id !== null) {
      $request_payload["bridge_id"] = $bridge_id;
    }

    $this->seam->request(
      "POST",
      "/bridges/get",
      json: (object) $request_payload,
      inner_object: "bridge"
    );
  }

  public function list(): void
  {
    $request_payload = [];

    $this->seam->request(
      "POST",
      "/bridges/list",
      json: (object) $request_payload,
      inner_object: "bridges"
    );
  }
}
