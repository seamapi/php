<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\Network;

class NetworksClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $network_id): Network
  {
    $request_payload = [];

    if ($network_id !== null) {
      $request_payload["network_id"] = $network_id;
    }

    $res = $this->seam->request(
      "POST",
      "/networks/get",
      json: (object) $request_payload,
      inner_object: "network"
    );

    return Network::from_json($res);
  }

  public function list(): array
  {
    $request_payload = [];

    $res = $this->seam->request(
      "POST",
      "/networks/list",
      json: (object) $request_payload,
      inner_object: "networks"
    );

    return array_map(fn($r) => Network::from_json($r), $res);
  }
}
