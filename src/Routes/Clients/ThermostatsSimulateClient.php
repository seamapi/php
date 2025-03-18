<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class ThermostatsSimulateClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function hvac_mode_adjusted(
    string $device_id,
    string $hvac_mode,
    float $cooling_set_point_celsius = null,
    float $cooling_set_point_fahrenheit = null,
    float $heating_set_point_celsius = null,
    float $heating_set_point_fahrenheit = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($hvac_mode !== null) {
      $request_payload["hvac_mode"] = $hvac_mode;
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

    $this->seam->request(
      "POST",
      "/thermostats/simulate/hvac_mode_adjusted",
      json: (object) $request_payload
    );
  }

  public function temperature_reached(
    string $device_id,
    float $temperature_celsius = null,
    float $temperature_fahrenheit = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($temperature_celsius !== null) {
      $request_payload["temperature_celsius"] = $temperature_celsius;
    }
    if ($temperature_fahrenheit !== null) {
      $request_payload["temperature_fahrenheit"] = $temperature_fahrenheit;
    }

    $this->seam->request(
      "POST",
      "/thermostats/simulate/temperature_reached",
      json: (object) $request_payload
    );
  }
}
