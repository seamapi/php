<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class ClimateSettingSchedulesTest extends TestCase
{
    public function testClimateSettingSchedules(): void
    {
        $seam = Fixture::getTestServer();

        $devices = $seam->devices->list();
        $thermostat_id = $devices[0]->device_id;

        $schedule_start_date = new DateTime("+1 months");
        $schedule_end_date = new DateTime("+2 months");

        $climate_setting_schedule = $seam->thermostats->climate_setting_schedules->create(
            device_id: $thermostat_id,
            name: "Test name",
            schedule_starts_at: $schedule_start_date->format(DateTime::ATOM),
            schedule_ends_at: $schedule_end_date->format(DateTime::ATOM),
            hvac_mode_setting: "cool",
            cooling_set_point_celsius: 20,
            manual_override_allowed: true
        );
        $this->assertTrue($climate_setting_schedule->name === "Test name");

        $climate_setting_schedules = $seam->thermostats->climate_setting_schedules->list(
            device_id: $thermostat_id
        );
        $this->assertTrue(count($climate_setting_schedules) > 0);

        $climate_setting_schedule_id =
            $climate_setting_schedules[0]->climate_setting_schedule_id;
        $climate_setting_schedule = $seam->thermostats->climate_setting_schedules->get(
            climate_setting_schedule_id: $climate_setting_schedule_id
        );
        $this->assertTrue(
            $climate_setting_schedule->climate_setting_schedule_id ===
                $climate_setting_schedule_id
        );

        $seam->thermostats->climate_setting_schedules->update(
            climate_setting_schedule_id: $climate_setting_schedule_id,
            hvac_mode_setting: "cool",
            cooling_set_point_celsius: 21,
            manual_override_allowed: true
        );
        $updated_climate_setting_schedule = $seam->thermostats->climate_setting_schedules->get(
          climate_setting_schedule_id: $climate_setting_schedule_id
      );
        $this->assertTrue(
            $updated_climate_setting_schedule->cooling_set_point_celsius == 21
        );

        $seam->thermostats->climate_setting_schedules->delete(
            climate_setting_schedule_id: $climate_setting_schedule_id
        );

        try {
            $climate_setting_schedule = $seam->thermostats->climate_setting_schedules->get(
                climate_setting_schedule_id: $climate_setting_schedule_id
            );

            $this->fail("Expected climate_setting_schedule not to be found");
        } catch (Exception $exception) {
            $this->assertTrue(
                str_contains(
                    $exception->getMessage(),
                    "climate_setting_schedule_not_found"
                )
            );
        }
    }
}
