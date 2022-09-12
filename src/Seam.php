<?php

namespace Seam;

use GuzzleHttp\Client;

final class Seam
{
  public function __construct($api_key, $endpoint = 'https://connect.getseam.com')
  {
    $this->api_key = $api_key;
    $this->client = new Client([
      'base_uri' => $endpoint,
      'timeout'  => 10.0,
      'headers'  => ['Authorization' => 'Bearer ' . $this->api_key],
    ]);
    $this->devices = new Devices($this);
    $this->workspaces = new Workspaces($this);
  }
}

final class Devices
{
  public function __construct($seam)
  {
    $this->seam = $seam;
  }

  public function list()
  {
    $res = $this->seam->client->request("GET", "devices/list");
    return json_decode($res->getBody())->devices;
  }
}

final class Workspaces
{
  public function __construct($seam)
  {
    $this->seam = $seam;
  }

  public function get()
  {
    $res = $this->seam->client->request("GET", "workspaces/get");
    return json_decode($res->getBody())->workspace;
  }

  public function list()
  {
    $res = $this->seam->client->request("GET", "workspaces/list");
    return json_decode($res->getBody())->workspaces;
  }

  public function reset_sandbox()
  {
    $res = $this->seam->client->request("POST", "workspaces/reset_sandbox");
    return json_decode($res->getBody())->workspace;
  }

  public function _internal_load_august_factory()
  {
    $res = $this->seam->client->request("POST", "internal/scenarios/factories/load", [
      'json' => [
        'factory_name' => 'august',
        'input' => ['num' => 1],
        'sync' => true
      ],
    ]);

    // sleep for 0.2 seconds
    usleep(200000);
  }
}
