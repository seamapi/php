<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;

class DevicesSimulateClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function connect(string $device_id): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/devices/simulate/connect",
      json: (object) $request_payload
    );
  }

  public function disconnect(string $device_id): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/devices/simulate/disconnect",
      json: (object) $request_payload
    );
  }

  public function remove(string $device_id): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/devices/simulate/remove",
      json: (object) $request_payload
    );
  }
}
