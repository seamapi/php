<?php

namespace Seam;

use Seam\Objects\AccessCode;
use Seam\Objects\ActionAttempt;
use Seam\Objects\ConnectedAccount;
use Seam\Objects\ConnectWebview;
use Seam\Objects\Device;
use Seam\Objects\UnmanagedDevice;
use Seam\Objects\Event;
use Seam\Objects\Workspace;
use Seam\Objects\ClientSession;
use Seam\Objects\NoiseThreshold;
use Seam\Objects\ClimateSettingSchedule;
use Seam\Objects\DeviceProvider;

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
  public ActionAttemptsClient $action_attempts;
  public AccessCodesClient $access_codes;
  public EventsClient $events;
  public ConnectWebviewsClient $connect_webviews;
  public ConnectedAccountsClient $connected_accounts;
  public LocksClient $locks;
  public ClientSessionsClient $client_sessions;
  public NoiseSensorsClient $noise_sensors;

  public string $api_key;
  public HTTPClient $client;


  public function __construct(
    $api_key,
    $endpoint = "https://connect.getseam.com",
    $throw_http_errors = false
  ) {
    $this->api_key = $api_key;
    $this->client = new HTTPClient([
      "base_uri" => $endpoint,
      "timeout" => 60.0,
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
    $this->noise_sensors = new NoiseSensorsClient($this);
    $this->thermostats = new ThermostatsClient($this);
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
  public UnmanagedDevicesClient $unmanaged;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->unmanaged = new UnmanagedDevicesClient($seam);
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
    array $connected_account_ids = null,
    string $connect_webview_id = null,
    string $device_type = null,
    array $device_types = null,
    array $device_ids = null,
    string $manufacturer = null,
  ): array {
    $query = filter_out_null_params([
      "connected_account_id" => $connected_account_id,
      "connected_account_ids" => is_null($connected_account_ids) ? null : join(",", $connected_account_ids),
      "connect_webview_id" => $connect_webview_id,
      "device_ids" => is_null($device_ids) ? null : join(",", $device_ids),
      "device_type" => $device_type,
      "device_types" => is_null($device_types) ? null : join(",", $device_types),
      "manufacturer" => $manufacturer
    ]);

    return array_map(
      fn ($d) => Device::from_json($d),
      $this->seam->request("GET", "devices/list", query: $query, inner_object: "devices")
    );
  }

  /**
   * Update Device
   * @return void
   */
  public function update(
    string $device_id,
    string $name = null,
    mixed $location = null,
    bool $is_managed = null
  ) {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "name" => $name,
      "location" => $location,
      "is_managed" => $is_managed,
    ]);

    $this->seam->request(
      "PATCH",
      "devices/update",
      json: $json
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

  /**
   * List Device Providers
   * @return DeviceProvider[]
   */
  public function list_device_providers(
    string $provider_category = null,
  ): array {
    $query = filter_out_null_params([
      "provider_category" => $provider_category,
    ]);

    return array_map(
      fn ($dp) => DeviceProvider::from_json($dp),
      $this->seam->request(
        "GET",
        "devices/list_device_providers",
        query: $query,
        inner_object: "device_providers"
      )
    );
  }
}

class UnmanagedDevicesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(
    string $device_id = null,
    string $name = null,
  ): UnmanagedDevice {
    $query = filter_out_null_params([
      "device_id" => $device_id,
      "name" => $name,
    ]);

    return UnmanagedDevice::from_json(
      $this->seam->request(
        "GET",
        "devices/unmanaged/get",
        query: $query,
        inner_object: "device"
      )
    );
  }

  public function list(
    string $connected_account_id = null,
    array $connected_account_ids = null,
    string $connect_webview_id = null,
    string $device_type = null,
    array $device_types = null,
    string $manufacturer = null,
    array $device_ids = null,
  ): array {
    $query = filter_out_null_params([
      "connected_account_id" => $connected_account_id,
      "connected_account_ids" => is_null($connected_account_ids) ? null : join(",", $connected_account_ids),
      "connect_webview_id" => $connect_webview_id,
      "device_type" => $device_type,
      "device_types" => is_null($device_types) ? null : join(",", $device_types),
      "manufacturer" => $manufacturer,
      "device_ids" => is_null($device_ids) ? null : join(",", $device_ids),
    ]);

    return array_map(
      fn ($d) => UnmanagedDevice::from_json($d),
      $this->seam->request(
        "GET",
        "devices/unmanaged/list",
        query: $query,
        inner_object: "devices"
      )
    );
  }

  public function update(
    string $device_id,
    bool $is_managed,
  ) {
    $this->seam->request(
      "PATCH",
      "devices/unmanaged/update",
      json: [
        "device_id" => $device_id,
        "is_managed" => $is_managed,
      ]
    );
  }
}

class WorkspacesClient
{
  private SeamClient $seam;

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
    $res = $this->seam->request(
      "POST",
      "internal/scenarios/factories/load",
      json: [
        "factory_name" => "create_august_devices",
        "input" => ["num" => 2],
        "sync" => true,
      ],
    );

    // sleep for 0.2 seconds
    usleep(200000);
  }

  public function _internal_load_minut_factory()
  {
    $this->seam->request(
      "POST",
      "internal/scenarios/factories/load",
      json: [
        "factory_name" => "create_minut_devices",
        "input" => [
          "devicesConfig" => [
            [
              "sound_level_high" => [
                "value" => 60,
                "duration_seconds" => 600,
                "notifications" => []
              ],
              "sound_level_high_quiet_hours" => [
                "value" => 60,
                "duration_seconds" => 600,
                "notifications" => [],
                "enabled" => True,
                "starts_at" => "20:00",
                "ends_at" => "08:00",
              ]
            ]
          ],
        ],
        "sync" => true,
      ],
    );

    usleep(200000);
  }

  public function _internal_load_ecobee_factory()
  {
    $this->seam->request(
      "POST",
      "internal/scenarios/factories/load",
      json: [
        "factory_name" => "create_ecobee_devices",
        "input" => ["num" => 2],
        "sync" => true,
      ],
    );

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
  public UnmanagedAccessCodesClient $unmanaged;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->unmanaged = new UnmanagedAccessCodesClient($seam);
  }

  /**
   * List Access Codes
   * @return AccessCode[]
   */
  public function list(
    string $device_id = null,
    array $access_code_ids = null
  ): array {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "access_code_ids" => $access_code_ids,
    ]);

    return array_map(
      fn ($a) => AccessCode::from_json($a),
      $this->seam->request(
        "POST",
        "access_codes/list",
        json: $json,
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
    bool $allow_external_modification = null,
    string $common_code_key = null,
    bool $prefer_native_scheduling = null,
    bool $use_backup_access_code_pool = null,
    bool $wait_for_action_attempt = false
  ): ActionAttempt|AccessCode {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "name" => $name,
      "code" => $code,
      "starts_at" => $starts_at,
      "ends_at" => $ends_at,
      "attempt_for_offline_device" => $attempt_for_offline_device,
      "allow_external_modification" => $allow_external_modification,
      "common_code_key" => $common_code_key,
      "prefer_native_scheduling" => $prefer_native_scheduling,
      "use_backup_access_code_pool" => $use_backup_access_code_pool
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
    string $type = null,
    bool $allow_external_modification = null,
    bool $is_managed = null,
    bool $wait_for_access_code = true
  ): ActionAttempt|AccessCode {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "device_id" => $device_id,
      "name" => $name,
      "code" => $code,
      "starts_at" => $starts_at,
      "ends_at" => $ends_at,
      "type" => $type,
      "allow_external_modification" => $allow_external_modification,
      "is_managed" => $is_managed,
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

  public function pull_backup_access_code(string $access_code_id): AccessCode
  {
    return AccessCode::from_json(
      $this->seam->request(
        "POST",
        "access_codes/pull_backup_access_code",
        json: ["access_code_id" => $access_code_id],
        inner_object: "backup_access_code"
      )
    );
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
    bool $allow_external_modification = null,
    bool $force = null,
  ) {
    $json = filter_out_null_params([
      "access_code_id" => $access_code_id,
      "is_managed" => $is_managed,
      "allow_external_modification" => $allow_external_modification,
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
    array $accepted_providers = [],
    string $custom_redirect_url = null,
    string $custom_redirect_failure_url = null,
    string $device_selection_mode = null,
    string $provider_category = null,
    mixed $custom_metadata = null,
    bool $automatically_manage_new_devices = null,
    bool $wait_for_device_creation = null
  ) {
    $json = filter_out_null_params([
      "accepted_providers" => $accepted_providers,
      "custom_redirect_url" => $custom_redirect_url,
      "custom_redirect_failure_url" => $custom_redirect_failure_url,
      "device_selection_mode" => $device_selection_mode,
      "provider_category" => $provider_category,
      "custom_metadata" => $custom_metadata,
      "automatically_manage_new_devices" => $automatically_manage_new_devices,
      "wait_for_device_creation" => $wait_for_device_creation,
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
  public function list(): array
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
    array | null $connect_webview_ids = null,
    array | null $connected_account_ids = null
  ) {
    $json = filter_out_null_params([
      "user_identifier_key" => $user_identifier_key,
      "connect_webview_ids" => $connect_webview_ids,
      "connected_account_ids" => $connected_account_ids
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

  public function get_or_create(
    string $user_identifier_key = null,
    array | null $connect_webview_ids = null,
    array | null $connected_account_ids = null
  ) {
    $json = filter_out_null_params([
      "user_identifier_key" => $user_identifier_key,
      "connect_webview_ids" => $connect_webview_ids,
      "connected_account_ids" => $connected_account_ids
    ]);

    // TODO change the /client_sessions/get_or_create when that's available
    return ClientSession::from_json(
      $this->seam->request(
        "PUT",
        "client_sessions/create",
        json: $json,
        inner_object: "client_session"
      )
    );
  }
}

class NoiseSensorsClient
{
  private SeamClient $seam;
  public NoiseThresholdsClient $noise_thresholds;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->noise_thresholds = new NoiseThresholdsClient($seam);
  }
}

class NoiseThresholdsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function list(string $device_id): array
  {
    return array_map(
      fn ($nt) => NoiseThreshold::from_json($nt),
      $this->seam->request(
        "GET",
        "noise_sensors/noise_thresholds/list",
        query: ["device_id" => $device_id],
        inner_object: "noise_thresholds"
      )
    );
  }

  public function create(
    string $device_id,
    string $starts_daily_at,
    string $ends_daily_at,
    string $name = null,
    float $noise_threshold_decibels = null,
    float $noise_threshold_nrs = null,
    bool $wait_for_action_attempt = true,
  ): ActionAttempt|NoiseThreshold {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "starts_daily_at" => $starts_daily_at,
      "ends_daily_at" => $ends_daily_at,
      "name" => $name,
      "noise_threshold_decibels" => $noise_threshold_decibels,
      "noise_threshold_nrs" => $noise_threshold_nrs,
    ]);

    $action_attempt = ActionAttempt::from_json(
      $this->seam->request(
        "POST",
        "noise_sensors/noise_thresholds/create",
        json: $json,
        inner_object: "action_attempt"
      )
    );

    if (!$wait_for_action_attempt) {
      return $action_attempt;
    }

    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);
    $noise_threshold = $updated_action_attempt->result?->noise_threshold;

    if (!$noise_threshold) {
      throw new Exception(
        "Failed to create noise threshold: no noise threshold returned: " .
          json_encode($updated_action_attempt)
      );
    }

    return NoiseThreshold::from_json($noise_threshold);
  }

  public function update(
    string $device_id,
    string $noise_threshold_id,
    string $name = null,
    string $starts_daily_at = null,
    string $ends_daily_at = null,
    float $noise_threshold_decibels = null,
    float $noise_threshold_nrs = null,
    bool $wait_for_action_attempt = true,
  ): ActionAttempt|NoiseThreshold {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "noise_threshold_id" => $noise_threshold_id,
      "name" => $name,
      "starts_daily_at" => $starts_daily_at,
      "ends_daily_at" => $ends_daily_at,
      "noise_threshold_decibels" => $noise_threshold_decibels,
      "noise_threshold_nrs" => $noise_threshold_nrs,
    ]);

    $action_attempt = ActionAttempt::from_json(
      $this->seam->request(
        "PUT",
        "noise_sensors/noise_thresholds/update",
        json: $json,
        inner_object: "action_attempt"
      )
    );

    if (!$wait_for_action_attempt) {
      return $action_attempt;
    }

    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);
    $noise_threshold = $updated_action_attempt->result?->noise_threshold;

    if (!$noise_threshold) {
      throw new Exception(
        "Failed to update noise threshold: no noise threshold returned: " .
          json_encode($updated_action_attempt)
      );
    }

    return NoiseThreshold::from_json($noise_threshold);
  }

  public function delete(
    string $noise_threshold_id,
    string $device_id,
    bool $wait_for_action_attempt = true,
  ) {
    $action_attempt = ActionAttempt::from_json(
      $this->seam->request(
        "DELETE",
        "noise_sensors/noise_thresholds/delete",
        json: [
          "noise_threshold_id" => $noise_threshold_id,
          "device_id" => $device_id
        ],
        inner_object: "action_attempt"
      )
    );

    if (!$wait_for_action_attempt) {
      return $action_attempt;
    }

    $updated_action_attempt = $this->seam->action_attempts->poll_until_ready($action_attempt->action_attempt_id);

    return $updated_action_attempt;
  }
}

class ThermostatsClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->climate_setting_schedules = new ClimateSettingSchedulesClient($seam);
  }

  /**
   * Get Thermostat
   * @return Device
   */
  public function get(string $device_id = null, string $name = null): Device
  {
    $query = filter_out_null_params(["device_id" => $device_id, "name" => $name]);

    $thermostat = Device::from_json(
      $this->seam->request(
        "GET",
        "thermostats/get",
        query: $query,
        inner_object: "thermostat"
      )
    );
    return $thermostat;
  }

  /**
   * List Thermostats
   * @return Device[]
   */
  public function list(
    string $connected_account_id = null,
    array $connected_account_ids = null,
    string $connect_webview_id = null,
    string $device_type = null,
    array $device_ids = null,
    string $manufacturer = null
  ): array {
    $query = filter_out_null_params([
      "connected_account_id" => $connected_account_id,
      "connected_account_ids" => is_null($connected_account_ids) ? null : join(",", $connected_account_ids),
      "connect_webview_id" => $connect_webview_id,
      "device_ids" => is_null($device_ids) ? null : join(",", $device_ids),
      "device_type" => $device_type,
      "manufacturer" => $manufacturer
    ]);

    return array_map(
      fn ($t) => Device::from_json($t),
      $this->seam->request("GET", "thermostats/list", query: $query, inner_object: "thermostats")
    );
  }

  /**
   * Update Thermostat
   * @return void
   */
  public function update(
    string $device_id,
    mixed $default_climate_setting = null
  ) {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "default_climate_setting" => $default_climate_setting,
    ]);

    $this->seam->request(
      "POST",
      "thermostats/update",
      json: $json
    );
  }
}

class ClimateSettingSchedulesClient
{
  private SeamClient $seam;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  /**
   * Get Climate Setting Schedule
   * @return ClimateSettingSchedule
   */
  public function get(
    string $climate_setting_schedule_id = null,
    string $device_id = null
  ): ClimateSettingSchedule {
    $query = filter_out_null_params([
      "climate_setting_schedule_id" => $climate_setting_schedule_id,
      "device_id" => $device_id
    ]);

    $climate_setting_schedule = ClimateSettingSchedule::from_json(
      $this->seam->request(
        "GET",
        "thermostats/climate_setting_schedules/get",
        query: $query,
        inner_object: "climate_setting_schedule"
      )
    );

    return $climate_setting_schedule;
  }

  /**
   * List Climate Setting Schedules
   * @return ClimateSettingSchedule[]
   */
  public function list(
    string $device_id,
  ): array {
    return array_map(
      fn ($t) => ClimateSettingSchedule::from_json($t),
      $this->seam->request(
        "GET",
        "thermostats/climate_setting_schedules/list",
        query: ["device_id" => $device_id],
        inner_object: "climate_setting_schedules"
      )
    );
  }

  /**
   * Create Climate Setting Schedule
   * @return ClimateSettingSchedule
   */
  public function create(
    string $device_id,
    string $schedule_starts_at,
    string $schedule_ends_at,
    bool $manual_override_allowed,
    string $name = null,
    string $schedule_type = null,
    bool $automatic_heating_enabled = null,
    bool $automatic_cooling_enabled = null,
    string $hvac_mode_setting = null,
    float $cooling_set_point_celsius = null,
    float $heating_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    float $heating_set_point_fahrenheit = null,
  ): ClimateSettingSchedule {
    $json = filter_out_null_params([
      "device_id" => $device_id,
      "schedule_starts_at" => $schedule_starts_at,
      "schedule_ends_at" => $schedule_ends_at,
      "name" => $name,
      "schedule_type" => $schedule_type,
      "automatic_heating_enabled" => $automatic_heating_enabled,
      "automatic_cooling_enabled" => $automatic_cooling_enabled,
      "hvac_mode_setting" => $hvac_mode_setting,
      "cooling_set_point_celsius" => $cooling_set_point_celsius,
      "heating_set_point_celsius" => $heating_set_point_celsius,
      "cooling_set_point_fahrenheit" => $cooling_set_point_fahrenheit,
      "heating_set_point_fahrenheit" => $heating_set_point_fahrenheit,
      "manual_override_allowed" => $manual_override_allowed,
    ]);

    return ClimateSettingSchedule::from_json(
      $this->seam->request(
        "POST",
        "thermostats/climate_setting_schedules/create",
        json: $json,
        inner_object: "climate_setting_schedule"
      )
    );
  }

  /**
   * Delete Climate Setting Schedule
   * @return void
   */
  public function delete(string $climate_setting_schedule_id)
  {
    $this->seam->request(
      "DELETE",
      "thermostats/climate_setting_schedules/delete",
      json: [
        "climate_setting_schedule_id" => $climate_setting_schedule_id,
      ]
    );
  }

  /**
   * Update Climate Setting Schedule
   * @return ClimateSettingSchedule
   */
  public function update(
    string $climate_setting_schedule_id,
    string $schedule_type = null,
    string $name = null,
    string $schedule_starts_at = null,
    string $schedule_ends_at = null,
    bool $automatic_heating_enabled = null,
    bool $automatic_cooling_enabled = null,
    string $hvac_mode_setting = null,
    float $cooling_set_point_celsius = null,
    float $heating_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    float $heating_set_point_fahrenheit = null,
    bool $manual_override_allowed = null,
  ): ClimateSettingSchedule {
    $json = filter_out_null_params([
      "climate_setting_schedule_id" => $climate_setting_schedule_id,
      "schedule_type" => $schedule_type,
      "name" => $name,
      "schedule_starts_at" => $schedule_starts_at,
      "schedule_ends_at" => $schedule_ends_at,
      "automatic_heating_enabled" => $automatic_heating_enabled,
      "automatic_cooling_enabled" => $automatic_cooling_enabled,
      "hvac_mode_setting" => $hvac_mode_setting,
      "cooling_set_point_celsius" => $cooling_set_point_celsius,
      "heating_set_point_celsius" => $heating_set_point_celsius,
      "cooling_set_point_fahrenheit" => $cooling_set_point_fahrenheit,
      "heating_set_point_fahrenheit" => $heating_set_point_fahrenheit,
      "manual_override_allowed" => $manual_override_allowed,
    ]);

    $updated_climate_setting_schedule = ClimateSettingSchedule::from_json(
      $this->seam->request(
        "PUT",
        "thermostats/climate_setting_schedules/update",
        json: $json,
        inner_object: "climate_setting_schedule"
      )
    );

    return $updated_climate_setting_schedule;
  }
}
