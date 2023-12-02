<?php

namespace Seam\Objects;

use stdClass;

use function Seam\filter_out_null_params;

class DeviceProperties extends stdClass
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
      "max_active_codes_supported" => $json->max_active_codes_supported ?? null,
      "model" => $json->model ?? null,
      "image_url" => $json->image_url ?? null,
      "image_alt_text" => $json->image_alt_text ?? null,

      "schlage_metadata" => $json->schlage_metadata ?? null,
      "august_metadata" => $json->august_metadata ?? null,
      "smartthings_metadata" => $json->smartthings_metadata ?? null,
      "nuki_metadata" => $json->nuki_metadata ?? null,
      "salto_metadata" => $json->salto_metadata ?? null,
      "controlbyweb_metadata" => $json->controlbyweb_metadata ?? null,
      "two_n_metadata" => $json->two_n_metadata ?? null,
      "kwikset_metadata" => $json->kwikset_metadata ?? null,
      "ttlock_metadata" => $json->ttlock_metadata ?? null,
      "igloohome_metadata" => $json->igloohome_metadata ?? null,
      "genie_metadata" => $json->genie_metadata ?? null,
      "brivo_metadata" => $json->brivo_metadata ?? null,
      "minut_metadata" => $json->minut_metadata ?? null,
      "noiseaware_metadata" => $json->noiseaware_metadata ?? null,
      "ecobee_metadata" => $json->ecobee_metadata ?? null,
      "nest_metadata" => $json->nest_metadata ?? null,

      "is_climate_setting_schedule_active" => $json->is_climate_setting_schedule_active ?? null,
      "active_climate_setting_schedule" => $json->active_climate_setting_schedule ?? null,
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

      "offline_access_codes_enabled" => $json->offline_access_codes_enabled ?? null,
      "online_access_codes_enabled" => $json->online_access_codes_enabled ?? null,
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

  public function __get($name): mixed
  {
      return $this->$name ?? null;
  }
}
