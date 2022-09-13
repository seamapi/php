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
    $this->action_attempts = new ActionAttemptsClient($this);
    $this->workspaces = new WorkspacesClient($this);
    $this->access_codes = new AccessCodesClient($this);
    $this->connect_webviews = new ConnectWebviewsClient($this);
    $this->connected_accounts = new ConnectedAccountsClient($this);
  }

  public function request($method, $path, $json = null, $query = null, $inner_object = null)
  {
    $options = [];
    if ($json) {
      $options['json'] = $json;
    }
    if ($query) {
      $options['query'] = $query;
    }

    // TODO handle request errors
    $response = $this->client->request($method, $path, $options);
    $res_json = json_decode($response->getBody());
    if ($inner_object) {
      return $res_json->$inner_object;
    }
    return $res_json;
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
   * Get Device
   */
  public function get(string $device_id): Device | null
  {
    $device = Device::from_json($this->seam->request(
      "GET",
      "devices/get",
      query: ['device_id' => $device_id],
      inner_object: 'device'
    ));
    return $device;
  }

  /**
   * List devices
   * @return Device[]
   */
  public function list(): array
  {
    return array_map(
      fn ($d) => Device::from_json($d),
      $this->seam->request("GET", "devices/list", inner_object: 'devices')
    );
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

final class ActionAttemptsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * List Action Attempts
   * @return ActionAttempt[]
   */
  public function list(): array
  {
    return array_map(
      fn ($a) => ActionAttempt::from_json($a),
      $this->seam->request("GET", "action_attempts/list", inner_object: 'action_attempts')
    );
  }

  public function get(string $action_attempt_id): ActionAttempt
  {
    return ActionAttempt::from_json($this->seam->request(
      "GET",
      "action_attempts/get",
      query: ['action_attempt_id' => $action_attempt_id],
      inner_object: 'action_attempt'
    ));
  }
}

final class AccessCodesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * List Access Codes
   * @return AccessCode[]
   */
  public function list(string $device_id = ""): array
  {
    return array_map(
      fn ($a) => AccessCode::from_json($a),
      $this->seam->request("GET", "access_codes/list", inner_object: 'access_codes')
    );
  }

  public function get(string $access_code_id): AccessCode
  {
    return AccessCode::from_json($this->seam->request(
      "GET",
      "access_codes/get",
      query: ['access_code_id' => $access_code_id],
      inner_object: 'access_code'
    ));
  }
}

final class ConnectWebviewsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function create($accepted_providers = [])
  {
    return ConnectWebview::from_json($this->seam->request(
      "POST",
      "connect_webviews/create",
      json: [
        'accepted_providers' => $accepted_providers
      ],
      inner_object: 'connect_webview'
    ));
  }

  /**
   * List Connect Webviews
   * @return ConnectWebview[]
   */
  public function list(string $device_id = ""): array
  {
    return array_map(
      fn ($a) => ConnectWebview::from_json($a),
      $this->seam->request("GET", "connect_webviews/list", inner_object: 'connect_webviews')
    );
  }

  public function get(string $connect_webview_id): ConnectWebview
  {
    return ConnectWebview::from_json($this->seam->request(
      "GET",
      "connect_webviews/get",
      query: ['connect_webview_id' => $connect_webview_id],
      inner_object: 'connect_webview'
    ));
  }
}

final class ConnectedAccountsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * List Connected Accounts
   * @return ConnectedAccount[]
   */
  public function list(): array
  {
    return array_map(
      fn ($a) => ConnectedAccount::from_json($a),
      $this->seam->request("GET", "connected_accounts/list", inner_object: 'connected_accounts')
    );
  }

  public function get(string $connected_account_id): ConnectedAccount
  {
    return ConnectedAccount::from_json($this->seam->request(
      "GET",
      "connected_accounts/get",
      query: ['connected_account_id' => $connected_account_id],
      inner_object: 'connected_account'
    ));
  }
}
