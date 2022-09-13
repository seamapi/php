<?php

namespace Seam;

use Seam\Workspace;
use Seam\Device;

use GuzzleHttp\Client as HTTPClient;

final class SeamClient
{
  public DevicesClient $devices;
  public WorkspacesClient $workspaces;

  public function __construct($api_key, $endpoint = 'https://connect.getseam.com')
  {
    $this->api_key = $api_key;
    $this->client = new HTTPClient([
      'base_uri' => $endpoint,
      'timeout'  => 10.0,
      'headers'  => ['Authorization' => 'Bearer ' . $this->api_key],
    ]);
    $this->devices = new DevicesClient($this);
    $this->workspaces = new WorkspacesClient($this);
  }
}

final class DevicesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * List devices
   * @return Device[]
   */
  public function list(): array
  {
    $res = $this->seam->client->request("GET", "devices/list");
    return json_decode($res->getBody())->devices;
  }
}

final class WorkspacesClient
{
  public function __construct($seam)
  {
    $this->seam = $seam;
  }

  public function get(): Workspace
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
    return json_decode($res->getBody());
  }

  public function _internal_load_august_factory()
  {
    $res = $this->seam->client->request("POST", "internal/scenarios/factories/load", [
      'json' => [
        'factory_name' => 'create_august_devices',
        'input' => ['num' => 1],
        'sync' => true
      ],
    ]);

    // sleep for 0.2 seconds
    usleep(200000);
  }
}
