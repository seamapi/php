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
