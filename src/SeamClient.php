<?php

namespace Seam;

use Seam\Objects\AccessCode;
use Seam\Objects\ActionAttempt;
use Seam\Objects\ConnectedAccount;
use Seam\Objects\ConnectWebview;
use Seam\Objects\Device;
use Seam\Objects\Event;
use Seam\Objects\Workspace;
use Seam\Objects\ClientSession;

use GuzzleHttp\Client as HTTPClient;
use \Exception as Exception;

function filter_out_null_params(array $params)
{
  return array_filter($params, fn ($p) => $p or $p === false ? true : false);
}

class SeamClient
{
  public DevicesClient $devices;
  public WorkspacesClient $workspaces;

  public function __construct(
    $api_key,
    $endpoint = "https://connect.getseam.com",
    $throw_http_errors = false
  ) {
    $this->api_key = $api_key;
    $this->client = new HTTPClient([
      "base_uri" => $endpoint,
      "timeout" => 20.0,
      "headers" => [
        "Authorization" => "Bearer " . $this->api_key,
        "User-Agent" => "Seam PHP Client 0.0.1",
      ],
      "http_errors" => $throw_http_errors,
    ]);
    $this->devices = new DevicesClient($this);
    $this->action_attempts = new ActionAttemptsClient($this);
    $this->workspaces = new WorkspacesClient($this);
    $this->access_codes = new AccessCodesClient($this);
    $this->events = new EventsClient($this);
    $this->connect_webviews = new ConnectWebviewsClient($this);
    $this->connected_accounts = new ConnectedAccountsClient($this);
    $this->locks = new LocksClient($this);
    $this->client_sessions = new ClientSessionsClient($this);
  }

  public function request(
    $method,
    $path,
    $json = null,
    $query = null,
    $inner_object = null
  ) {
    $options = filter_out_null_params(["json" => $json, "query" => $query]);

    // TODO handle request errors
    $response = $this->client->request($method, $path, $options);
    $statusCode = $response->getStatusCode();

    $res_json = null;
    try {
      $res_json = json_decode($response->getBody());
    } catch (Exception $ignoreError) {
    }

    if (($res_json->error ?? null) != null) {
      throw new Exception(
        "Error Calling \"" .
          $method .
          " " .
          $path .
          "\" : " .
          ($res_json->error->type ?? "") .
          ": " .
          $res_json->error->message
      );
    }

    if ($statusCode >= 400) {
      throw new Exception(
        "HTTP Error: [" . $statusCode . "] " . $method . " " . $path
      );
    }

    if ($inner_object) {
      if (!is_array($res_json->$inner_object) && ($res_json->$inner_object ?? null) == null) {
        throw new Exception(
          'Missing Inner Object "' .
            $inner_object .
            '" for ' .
            $method .
            " " .
            $path
        );
      }
      return $res_json->$inner_object;
    }
    return $res_json;
  }
}

class DevicesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * Get Device
   */
  public function get(string $device_id = null, string $name = null): Device|null
  {
    $query = filter_out_null_params(["device_id" => $device_id, "name" => $name]);

    $device = Device::from_json(
      $this->seam->request(
        "GET",
        "devices/get",
        query: $query,
        inner_object: "device"
      )
    );
    return $device;
  }

  /**
   * List devices
   * @return Device[]
   */
  public function list(
    string $connected_account_id = null,
    string $connect_webview_id = null,
    string $device_type = null,
    array $device_ids = null,
    string $manufacturer = null
  ): array {
    $query = filter_out_null_params([
      "connected_account_id" => $connected_account_id,
      "connect_webview_id" => $connect_webview_id,
      "device_ids" => is_null($device_ids) ? null : join(",", $device_ids),
      "device_type" => $device_type,
      "manufacturer" => $manufacturer
    ]);

    return array_map(
      fn ($d) => Device::from_json($d),
      $this->seam->request("GET", "devices/list", query: $query, inner_object: "devices")
    );
  }

  /**
   * Delete Device
   * @return void
   */
  public function delete(string $device_id)
  {
    $this->seam->request(
      "DELETE",
      "devices/delete",
      json: [
        "device_id" => $device_id,
      ]
    );
  }
}

class WorkspacesClient
{
  public function __construct($seam)
  {
    $this->seam = $seam;
  }

  public function get($workspace_id = null): Workspace
  {
    $query = filter_out_null_params(["workspace_id" => $workspace_id]);

    return Workspace::from_json(
      $this->seam->request("GET", "workspaces/get", query: $query, inner_object: "workspace")
    );
  }

  public function list($workspace_id = null)
  {
    $query = filter_out_null_params(["workspace_id" => $workspace_id]);

    return array_map(
      fn ($w) => Workspace::from_json($w),
      $this->seam->request("GET", "workspaces/list", query: $query,  inner_object: "workspaces")
    );
  }

  public function reset_sandbox()
  {
    $res = $this->seam->client->request("POST", "workspaces/reset_sandbox");
    return json_decode($res->getBody());
  }

  public function _internal_load_august_factory()
  {
    $res = $this->seam->client->request(
      "POST",
      "internal/scenarios/factories/load",
      [
        "json" => [
          "factory_name" => "create_august_devices",
          "input" => ["num" => 2],
          "sync" => true,
        ],
      ]
    );

    // sleep for 0.2 seconds
    usleep(200000);
  }
}

class ActionAttemptsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $action_attempt_id): ActionAttempt
  {
    return ActionAttempt::from_json(
      $this->seam->request(
        "GET",
        "action_attempts/get",
        query: ["action_attempt_id" => $action_attempt_id],
        inner_object: "action_attempt"
      )
    );
  }

  /**
   * List action attempts
   * @return ActionAttempt[]
   */
  public function list(array $action_attempt_ids): array
  {
    return array_map(
      fn ($a) => ActionAttempt::from_json($a),
      $this->seam->request(
        "GET",
        "action_attempts/list",
        query: ["action_attempt_ids" => implode(',', $action_attempt_ids)],
        inner_object: "action_attempts"
      )
    );
  }

  public function poll_until_ready(string $action_attempt_id): ActionAttempt
  {
    $seam = $this->seam;
    $time_waiting = 0.0;
    $action_attempt = $seam->action_attempts->get($action_attempt_id);

    while ($action_attempt->status == "pending") {
      $action_attempt = $seam->action_attempts->get(
        $action_attempt->action_attempt_id
      );
      if ($time_waiting > 20.0) {
        throw new Exception("Timed out waiting for access code to be created");
      }
      $time_waiting += 0.4;
      usleep(400000); // sleep for 0.4 seconds
    }

    if ($action_attempt->status == "failed") {
      throw new Exception(
        "Action Attempt failed: " . $action_attempt->error->message
      );
    }

    return ActionAttempt::from_json($action_attempt);
  }
}

class AccessCodesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->unmanaged = new UnmanagedAccessCodesClient($seam);
  }

  /**
   * List Access Codes
   * @return AccessCode[]
   */
  public function list(string $device_id = ""): array
  {
    return array_map(
      fn ($a) => AccessCode::from_json($a),
      $this->seam->request(
        "GET",
        "access_codes/list",
        query: ["device_id" => $device_id],
        inner_object: "access_codes"
      )
    );
  }

  public function get(
    string $access_code_id = null,
    string $device_id = null,
    string $code = null
  ): AccessCode {
    $query = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "device_id" => $device_id,
      "code" => $code
    ]);

    return AccessCode::from_json(
      $this->seam->request(
        "GET",
        "access_codes/get",
        query: $query,
        inner_object: "access_code"
      )
    );
  }

  public function create(
    string $device_id,
    string $name = null,
    string $code = null,
    string $starts_at = null,
    string $ends_at = null,
    bool $attempt_for_offline_device = null,
    bool $wait_for_action_attempt = false
  ): ActionAttempt|AccessCode {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "name" => $name,
      "code" => $code,
      "starts_at" => $starts_at,
      "ends_at" => $ends_at,
      "attempt_for_offline_device" => $attempt_for_offline_device
    ]);
    [
      'action_attempt' => $action_attempt,
      'access_code' => $access_code
    ] = (array) $this->seam->request(
      "POST",
      "access_codes/create",
      json: $json,
    );

    if (!$wait_for_action_attempt) {
      return AccessCode::from_json($access_code);
    }

    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);

    if (!$updated_action_attempt->result?->access_code) {
      throw new Exception(
        "Failed to create access code: no access code returned: " .
          json_encode($updated_action_attempt)
      );
    }

    return AccessCode::from_json($updated_action_attempt->result->access_code);
  }


  /**
   * Create Access Codes across multiple Devices that share a common code
   * 
   * @param string[] $device_ids
   * @param string|null $name
   * @param string|null $starts_at
   * @param string|null $ends_at
   * @param string|null $behavior_when_code_cannot_be_shared Accepts either "throw" or "create_random_code"
   * @param bool|null $attempt_for_offline_device
   * 
   * @return AccessCode[]
   */
  public function create_multiple(
    array $device_ids,
    string $name = null,
    string $starts_at = null,
    string $ends_at = null,
    string $behavior_when_code_cannot_be_shared = null,
    bool $attempt_for_offline_device = null,
  ): array {
    $json = filter_out_null_params([
      "device_ids" => $device_ids,
      "name" => $name,
      "starts_at" => $starts_at,
      "ends_at" => $ends_at,
      "behavior_when_code_cannot_be_shared" => $behavior_when_code_cannot_be_shared,
      "attempt_for_offline_device" => $attempt_for_offline_device
    ]);

    return array_map(
      fn ($a) => AccessCode::from_json($a),
      $this->seam->request(
        "POST",
        "access_codes/create_multiple",
        json: $json,
        inner_object: "access_codes"
      )
    );
  }

  public function delete(
    string $access_code_id,
    string $device_id = null,
    bool $wait_for_action_attempt = true,
  ): ActionAttempt {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "device_id" => $device_id,
    ]);
    $action_attempt = ActionAttempt::from_json(
      $this->seam->request(
        "POST",
        "access_codes/delete",
        json: $json,
        inner_object: "action_attempt"
      )
    );

    if (!$wait_for_action_attempt) {
      return $action_attempt;
    }

    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);

    return $updated_action_attempt;
  }

  public function update(
    string $access_code_id,
    string $device_id = null,
    string $name = null,
    string $code = null,
    string $starts_at = null,
    string $ends_at = null,
    bool $wait_for_access_code = true
  ): ActionAttempt|AccessCode {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "device_id" => $device_id,
      "name" => $name,
      "code" => $code,
      "starts_at" => $starts_at,
      "ends_at" => $ends_at
    ]);
    $action_attempt = ActionAttempt::from_json(
      $this->seam->request(
        "POST",
        "access_codes/update",
        json: $json,
        inner_object: "action_attempt"
      )
    );
    if (!$wait_for_access_code) {
      return $action_attempt;
    }
    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);

    if (!$updated_action_attempt->result?->access_code) {
      throw new Exception(
        "Failed to update access code: no access code returned: " .
          json_encode($updated_action_attempt)
      );
    }

    return AccessCode::from_json($updated_action_attempt->result->access_code);
  }
}

class UnmanagedAccessCodesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function list(string $device_id): array
  {
    return array_map(
      fn ($a) => AccessCode::from_json($a),
      $this->seam->request(
        "GET",
        "access_codes/unmanaged/list",
        query: ["device_id" => $device_id],
        inner_object: "access_codes"
      )
    );
  }

  public function update(
    string $access_code_id,
    bool $is_managed,
    bool $force = null
  ) {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "is_managed" => $is_managed,
      "force" => $force
    ]);

    $this->seam->request(
      "PATCH",
      "access_codes/unmanaged/update",
      json: $json
    );
  }

  public function delete(string $access_code_id, bool $sync = null): ActionAttempt
  {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "sync" => $sync
    ]);

    return ActionAttempt::from_json(
      $this->seam->request(
        "DELETE",
        "access_codes/unmanaged/delete",
        json: $json,
        inner_object: "action_attempt"
      )
    );
  }
}

class ConnectWebviewsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function create(
    $accepted_providers = [],
    string $custom_redirect_url = null,
    string $custom_redirect_failure_url = null,
    string $device_selection_mode = null
  ) {
    $json = filter_out_null_params([
      "accepted_providers" => $accepted_providers,
      "custom_redirect_url" => $custom_redirect_url,
      "custom_redirect_failure_url" => $custom_redirect_failure_url,
      "device_selection_mode" => $device_selection_mode
    ]);

    return ConnectWebview::from_json(
      $this->seam->request(
        "POST",
        "connect_webviews/create",
        json: $json,
        inner_object: "connect_webview"
      )
    );
  }

  /**
   * List Connect Webviews
   * @return ConnectWebview[]
   */
  public function list(string $device_id = ""): array
  {
    return array_map(
      fn ($a) => ConnectWebview::from_json($a),
      $this->seam->request(
        "GET",
        "connect_webviews/list",
        inner_object: "connect_webviews"
      )
    );
  }

  public function get(string $connect_webview_id): ConnectWebview
  {
    return ConnectWebview::from_json(
      $this->seam->request(
        "GET",
        "connect_webviews/get",
        query: ["connect_webview_id" => $connect_webview_id],
        inner_object: "connect_webview"
      )
    );
  }
}

class ConnectedAccountsClient
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
      $this->seam->request(
        "GET",
        "connected_accounts/list",
        inner_object: "connected_accounts"
      )
    );
  }

  public function delete(string $connected_account_id)
  {
    $this->seam->request(
      "DELETE",
      "connected_accounts/delete",
      json: [
        "connected_account_id" => $connected_account_id,
      ]
    );
  }

  public function get(string $connected_account_id): ConnectedAccount
  {
    return ConnectedAccount::from_json(
      $this->seam->request(
        "GET",
        "connected_accounts/get",
        query: ["connected_account_id" => $connected_account_id],
        inner_object: "connected_account"
      )
    );
  }
}

class EventsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * Get Event
   */
  public function get(
    string $event_id = null,
    string $event_type = null,
    string $device_id = null,
  ): Event|null {
    $query = filter_out_null_params([
      "event_id" => $event_id,
      "event_type" => $event_type,
      "device_id" => $device_id,
    ]);

    $event = Event::from_json(
      $this->seam->request(
        "GET",
        "events/get",
        query: $query,
        inner_object: "event"
      )
    );

    return $event;
  }

  /**
   * List Events
   * @return Event[]
   */
  public function list(
    string $since = null,
    array $between = null,
    string $device_id = null,
    array $device_ids = null,
    string $access_code_id = null,
    array $access_code_ids = null,
    string $event_type = null,
    array $event_types = null,
    string $connected_account_id = null,
  ): array {
    $query = filter_out_null_params([
      "since" => $since,
      "between" => $between,
      "device_id" => $device_id,
      "device_ids" => is_null($device_ids) ? null : join(",", $device_ids),
      "access_code_id" => $access_code_id,
      "access_code_ids" => is_null($access_code_ids) ? null : join(",", $access_code_ids),
      "event_type" => $event_type,
      "event_types" => $event_types,
      "connected_account_id" => $connected_account_id,
    ]);

    return array_map(
      fn ($d) => Event::from_json($d),
      $this->seam->request(
        "GET",
        "events/list",
        query: $query,
        inner_object: "events"
      )
    );
  }
}

class LocksClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * Get Lock
   */
  public function get(string $device_id = null, string $name = null): Device|null
  {
    $query = filter_out_null_params([
      "device_id" => $device_id,
      "name" => $name,
    ]);

    $device = Device::from_json(
      $this->seam->request(
        "GET",
        "locks/get",
        query: $query,
        inner_object: "lock"
      )
    );
    return $device;
  }

  /**
   * List Events
   * @return Device[]
   */
  public function list(
    string $connected_account_id = null,
    string $connect_webview_id = null
  ): array {
    $query = filter_out_null_params([
      "connected_account_id" => $connected_account_id,
      "connect_webview_id" => $connect_webview_id,
    ]);

    return array_map(
      fn ($d) => Device::from_json($d),
      $this->seam->request(
        "GET",
        "locks/list",
        query: $query,
        inner_object: "locks"
      )
    );
  }

  public function lock_door(string $device_id): ActionAttempt
  {
    return ActionAttempt::from_json(
      $this->seam->request(
        "POST",
        "locks/lock_door",
        json: [
          "device_id" => $device_id,
        ],
        inner_object: "action_attempt"
      )
    );
  }

  public function unlock_door(string $device_id): ActionAttempt
  {
    return ActionAttempt::from_json(
      $this->seam->request(
        "POST",
        "locks/unlock_door",
        json: [
          "device_id" => $device_id,
        ],
        inner_object: "action_attempt"
      )
    );
  }
}

class ClientSessionsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }
  
  public function create(
    string $user_identifier_key = null,
    $connect_webview_ids = null,
    $connected_account_ids = null
  ) {
    $json = filter_out_null_params([
      "user_identifier_key" => $user_identifier_key,
      "connect_webview_ids" => $connect_webview_ids,
      "connected_account_ids" => $connect_account_ids
    ]);

    return ClientSession::from_json(
      $this->seam->request(
        "POST",
        "client_sessions/create",
        json: $json,
        inner_object: "client_session"
      )
    );
  }
}
