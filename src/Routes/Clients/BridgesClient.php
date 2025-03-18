<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class BridgesClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
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
