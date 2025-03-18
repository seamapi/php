<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ThermostatSchedule;

class ThermostatsSchedulesClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create(
    string $climate_preset_key,
    string $device_id,
    string $ends_at,
    string $starts_at,
    bool $is_override_allowed = null,
    mixed $max_override_period_minutes = null,
    string $name = null
  ): ThermostatSchedule {
    $request_payload = [];

    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }
    if ($is_override_allowed !== null) {
      $request_payload["is_override_allowed"] = $is_override_allowed;
    }
    if ($max_override_period_minutes !== null) {
      $request_payload["max_override_period_minutes"] = $max_override_period_minutes;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/schedules/create",
      json: (object) $request_payload,
      inner_object: "thermostat_schedule"
    );

    return ThermostatSchedule::from_json($res);
  }

  public function delete(string $thermostat_schedule_id): void
  {
    $request_payload = [];

    if ($thermostat_schedule_id !== null) {
      $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;
    }

    $this->seam->request(
      "POST",
      "/thermostats/schedules/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $thermostat_schedule_id): ThermostatSchedule
  {
    $request_payload = [];

    if ($thermostat_schedule_id !== null) {
      $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/schedules/get",
      json: (object) $request_payload,
      inner_object: "thermostat_schedule"
    );

    return ThermostatSchedule::from_json($res);
  }

  public function list(
    string $device_id,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/thermostats/schedules/list",
      json: (object) $request_payload,
      inner_object: "thermostat_schedules"
    );

    return array_map(fn($r) => ThermostatSchedule::from_json($r), $res);
  }

  public function update(
    string $thermostat_schedule_id,
    string $climate_preset_key = null,
    string $ends_at = null,
    bool $is_override_allowed = null,
    mixed $max_override_period_minutes = null,
    string $name = null,
    string $starts_at = null
  ): void {
    $request_payload = [];

    if ($thermostat_schedule_id !== null) {
      $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;
    }
    if ($climate_preset_key !== null) {
      $request_payload["climate_preset_key"] = $climate_preset_key;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_override_allowed !== null) {
      $request_payload["is_override_allowed"] = $is_override_allowed;
    }
    if ($max_override_period_minutes !== null) {
      $request_payload["max_override_period_minutes"] = $max_override_period_minutes;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }

    $this->seam->request(
      "POST",
      "/thermostats/schedules/update",
      json: (object) $request_payload
    );
  }
}
