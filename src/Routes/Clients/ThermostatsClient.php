<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\ActionAttempt;
use Seam\Routes\Objects\Device;

class ThermostatsClient
{
  private SeamClient $seam;
  public ThermostatsSchedulesClient $schedules;
  public ThermostatsSimulateClient $simulate;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->schedules = new ThermostatsSchedulesClient($seam);
    $this->simulate = new ThermostatsSimulateClient($seam);
  }

  public function activate_climate_preset(
    string $climate_preset_key,
    string $device_id,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/activate_climate_preset",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function cool(
    string $device_id,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($cooling_set_point_celsius !== null) {
      $request_payload["cooling_set_point_celsius"] = $cooling_set_point_celsius;
    }
    if ($cooling_set_point_fahrenheit !== null) {
      $request_payload["cooling_set_point_fahrenheit"] = $cooling_set_point_fahrenheit;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/cool",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function create_climate_preset(
    string $climate_preset_key,
    string $device_id,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    string $fan_mode_setting = null,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null,
    string $hvac_mode_setting = null,
    bool $manual_override_allowed = null,
    string $name = null
  ): void {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($cooling_set_point_celsius !== null) {
      $request_payload["cooling_set_point_celsius"] = $cooling_set_point_celsius;
    }
    if ($cooling_set_point_fahrenheit !== null) {
      $request_payload["cooling_set_point_fahrenheit"] = $cooling_set_point_fahrenheit;
    }
    if ($fan_mode_setting !== null) {
      $request_payload["fan_mode_setting"] = $fan_mode_setting;
    }
    if ($heating_set_point_celsius !== null) {
      $request_payload["heating_set_point_celsius"] = $heating_set_point_celsius;
    }
    if ($heating_set_point_fahrenheit !== null) {
      $request_payload["heating_set_point_fahrenheit"] = $heating_set_point_fahrenheit;
    }
    if ($hvac_mode_setting !== null) {
      $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
    }
    if ($manual_override_allowed !== null) {
      $request_payload["manual_override_allowed"] = $manual_override_allowed;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $this->seam->request(
      "POST",
      "/thermostats/create_climate_preset",
      json: (object) $request_payload
    );
  }

  public function delete_climate_preset(
    string $climate_preset_key,
    string $device_id
  ): void {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/thermostats/delete_climate_preset",
      json: (object) $request_payload
    );
  }

  public function get(string $device_id = null, string $name = null): Device
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/get",
      json: (object) $request_payload,
      inner_object: "thermostat"
    );

    return Device::from_json($res);
  }

  public function heat(
    string $device_id,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($heating_set_point_celsius !== null) {
      $request_payload["heating_set_point_celsius"] = $heating_set_point_celsius;
    }
    if ($heating_set_point_fahrenheit !== null) {
      $request_payload["heating_set_point_fahrenheit"] = $heating_set_point_fahrenheit;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/heat",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function heat_cool(
    string $device_id,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($cooling_set_point_celsius !== null) {
      $request_payload["cooling_set_point_celsius"] = $cooling_set_point_celsius;
    }
    if ($cooling_set_point_fahrenheit !== null) {
      $request_payload["cooling_set_point_fahrenheit"] = $cooling_set_point_fahrenheit;
    }
    if ($heating_set_point_celsius !== null) {
      $request_payload["heating_set_point_celsius"] = $heating_set_point_celsius;
    }
    if ($heating_set_point_fahrenheit !== null) {
      $request_payload["heating_set_point_fahrenheit"] = $heating_set_point_fahrenheit;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/heat_cool",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function list(
    string $connect_webview_id = null,
    string $connected_account_id = null,
    array $connected_account_ids = null,
    string $created_before = null,
    mixed $custom_metadata_has = null,
    array $device_ids = null,
    string $device_type = null,
    array $device_types = null,
    array $exclude_if = null,
    array $include_if = null,
    float $limit = null,
    string $manufacturer = null,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($connect_webview_id !== null) {
      $request_payload["connect_webview_id"] = $connect_webview_id;
    }
    if ($connected_account_id !== null) {
      $request_payload["connected_account_id"] = $connected_account_id;
    }
    if ($connected_account_ids !== null) {
      $request_payload["connected_account_ids"] = $connected_account_ids;
    }
    if ($created_before !== null) {
      $request_payload["created_before"] = $created_before;
    }
    if ($custom_metadata_has !== null) {
      $request_payload["custom_metadata_has"] = $custom_metadata_has;
    }
    if ($device_ids !== null) {
      $request_payload["device_ids"] = $device_ids;
    }
    if ($device_type !== null) {
      $request_payload["device_type"] = $device_type;
    }
    if ($device_types !== null) {
      $request_payload["device_types"] = $device_types;
    }
    if ($exclude_if !== null) {
      $request_payload["exclude_if"] = $exclude_if;
    }
    if ($include_if !== null) {
      $request_payload["include_if"] = $include_if;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }
    if ($manufacturer !== null) {
      $request_payload["manufacturer"] = $manufacturer;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/list",
      json: (object) $request_payload,
      inner_object: "devices"
    );

    return array_map(fn($r) => Device::from_json($r), $res);
  }

  public function off(
    string $device_id,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/off",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function set_fallback_climate_preset(
    string $climate_preset_key,
    string $device_id
  ): void {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/thermostats/set_fallback_climate_preset",
      json: (object) $request_payload
    );
  }

  public function set_fan_mode(
    string $device_id,
    string $fan_mode = null,
    string $fan_mode_setting = null,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($fan_mode !== null) {
      $request_payload["fan_mode"] = $fan_mode;
    }
    if ($fan_mode_setting !== null) {
      $request_payload["fan_mode_setting"] = $fan_mode_setting;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/set_fan_mode",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function set_hvac_mode(
    string $device_id,
    string $hvac_mode_setting,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($hvac_mode_setting !== null) {
      $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
    }
    if ($cooling_set_point_celsius !== null) {
      $request_payload["cooling_set_point_celsius"] = $cooling_set_point_celsius;
    }
    if ($cooling_set_point_fahrenheit !== null) {
      $request_payload["cooling_set_point_fahrenheit"] = $cooling_set_point_fahrenheit;
    }
    if ($heating_set_point_celsius !== null) {
      $request_payload["heating_set_point_celsius"] = $heating_set_point_celsius;
    }
    if ($heating_set_point_fahrenheit !== null) {
      $request_payload["heating_set_point_fahrenheit"] = $heating_set_point_fahrenheit;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/set_hvac_mode",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function set_temperature_threshold(
    string $device_id,
    float $lower_limit_celsius = null,
    float $lower_limit_fahrenheit = null,
    float $upper_limit_celsius = null,
    float $upper_limit_fahrenheit = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($lower_limit_celsius !== null) {
      $request_payload["lower_limit_celsius"] = $lower_limit_celsius;
    }
    if ($lower_limit_fahrenheit !== null) {
      $request_payload["lower_limit_fahrenheit"] = $lower_limit_fahrenheit;
    }
    if ($upper_limit_celsius !== null) {
      $request_payload["upper_limit_celsius"] = $upper_limit_celsius;
    }
    if ($upper_limit_fahrenheit !== null) {
      $request_payload["upper_limit_fahrenheit"] = $upper_limit_fahrenheit;
    }

    $this->seam->request(
      "POST",
      "/thermostats/set_temperature_threshold",
      json: (object) $request_payload
    );
  }

  public function update_climate_preset(
    string $climate_preset_key,
    string $device_id,
    bool $manual_override_allowed,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    string $fan_mode_setting = null,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null,
    string $hvac_mode_setting = null,
    string $name = null
  ): void {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($manual_override_allowed !== null) {
      $request_payload["manual_override_allowed"] = $manual_override_allowed;
    }
    if ($cooling_set_point_celsius !== null) {
      $request_payload["cooling_set_point_celsius"] = $cooling_set_point_celsius;
    }
    if ($cooling_set_point_fahrenheit !== null) {
      $request_payload["cooling_set_point_fahrenheit"] = $cooling_set_point_fahrenheit;
    }
    if ($fan_mode_setting !== null) {
      $request_payload["fan_mode_setting"] = $fan_mode_setting;
    }
    if ($heating_set_point_celsius !== null) {
      $request_payload["heating_set_point_celsius"] = $heating_set_point_celsius;
    }
    if ($heating_set_point_fahrenheit !== null) {
      $request_payload["heating_set_point_fahrenheit"] = $heating_set_point_fahrenheit;
    }
    if ($hvac_mode_setting !== null) {
      $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $this->seam->request(
      "POST",
      "/thermostats/update_climate_preset",
      json: (object) $request_payload
    );
  }
}
