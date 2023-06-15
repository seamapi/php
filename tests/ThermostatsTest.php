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

    $seam->thermostats->delete(
      device_id: $thermostat_id
    );

    try {
      $seam->thermostats->get(device_id: $thermostat_id);

      $this->fail("Expected device not to be found");
    } catch (Exception $exception) {
      $this->assertTrue(str_contains($exception->getMessage(), "device_not_found"));
    }
  }
}
