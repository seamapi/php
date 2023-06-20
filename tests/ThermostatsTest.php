<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class ThermostatsTest extends TestCase
{
  public function testThermostats(): void
  {
    $seam = Fixture::getTestServer();
    $seam->workspaces->_internal_load_ecobee_factory();

    $devices = $seam->devices->list();
    $thermostat_id = $devices[0]->device_id;

    $thermostat = $seam->thermostats->get(
      device_id: $thermostat_id
    );
    $this->assertTrue($thermostat->device_id === $thermostat_id);

    $thermostats = $seam->thermostats->list();
    $this->assertTrue(count($thermostats) > 0);

    $seam->thermostats->update(
      device_id: $thermostat_id,
      default_climate_setting: [
        "hvac_mode_setting" => "cool",
        "cooling_set_point_celsius" => 20,
        "manual_override_allowed" => true,
      ]
    );

    $updated_thermostat = $seam->thermostats->get(
      device_id: $thermostat_id
    );
    $this->assertTrue(
      $updated_thermostat->properties?->default_climate_setting?->hvac_mode_setting === "cool"
    );

    $this->assertTrue(
      $updated_thermostat->properties?->current_climate_setting?->hvac_mode_setting === "heatcool"
    );
    $seam->thermostats->set_mode(device_id: $thermostat_id, hvac_mode_setting: "cool");
    $updated_thermostat = $seam->thermostats->get(
      device_id: $thermostat_id
    );
    $this->assertTrue(
      $updated_thermostat->properties?->current_climate_setting?->hvac_mode_setting === "cool"
    );

    $seam->thermostats->set_cooling_set_point(device_id: $thermostat_id, cooling_set_point_fahrenheit: 74);
    $updated_thermostat = $seam->thermostats->get(
      device_id: $thermostat_id
    );
    $this->assertTrue(
      $updated_thermostat->properties?->current_climate_setting?->cooling_set_point_fahrenheit === 74
    );
  }
}
