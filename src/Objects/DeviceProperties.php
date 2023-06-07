<?php

namespace Seam\Objects;

use function Seam\filter_out_null_params;

class DeviceProperties
{
  public static function from_json(mixed $json): DeviceProperties|null
  {
    if (!$json) {
      return null;
    }

    $props = filter_out_null_params([
      "online" => $json->online ?? null,
      "locked" => $json->locked ?? null,
      "name" => $json->name ?? null,
      "door_open" => $json->door_open ?? null,
      "battery_level" => $json->battery_level ?? null,
      "battery" => $json->battery ?? null,
      "manufacturer" => $json->manufacturer ?? null,
      "serial_number" => $json->serial_number ?? null,
      "supported_code_lengths" => $json->supported_code_lengths ?? null,
      "code_constraints" => $json->code_constraints ?? null,

      "schlage_metadata" => $json->schlage_metadata ?? null,
      "august_metadata" => $json->august_metadata ?? null,
      "smartthings_metadata" => $json->smartthings_metadata ?? null,
      "ecobee_metadata" => $json->ecobee_metadata ?? null,
      "nest_metadata" => $json->nest_metadata ?? null,

      "is_climate_setting_schedule_active" => $json->is_climate_setting_schedule_active ?? null,
      "is_cooling" => $json->is_cooling ?? null,
      "is_heating" => $json->is_heating ?? null,
      "is_fan_running" => $json->is_fan_running ?? null,
      "has_direct_power" => $json->has_direct_power ?? null,
      "relative_humidity" => $json->relative_humidity ?? null,
      "temperature_celsius" => $json->temperature_celsius ?? null,
      "temperature_fahrenheit" => $json->temperature_fahrenheit ?? null,
      "current_climate_setting" => $json->current_climate_setting ?? null,
      "default_climate_setting" => $json->default_climate_setting ?? null,
      "available_hvac_mode_settings" => $json->available_hvac_mode_settings ?? null,
      "can_enable_automatic_cooling" => $json->can_enable_automatic_cooling ?? null,
      "can_enable_automatic_heating" => $json->can_enable_automatic_heating ?? null,
    ]);

    return new self($props);
  }

  public function __construct(
    array $params
  ) {
    foreach ($params as $key => $value) {
      $this->$key = $value;
    }
  }
}
