<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class NoiseThresholdsTest extends TestCase
{
    public function testNoiseThresholds(): void
    {
        $seam = Fixture::getTestServer();

        function getMinutDeviceNoiseThresholds($seam, $device_id)
        {
            return $seam->noise_sensors->noise_thresholds->list(
                device_id: $device_id
            );
        }

        $devices = $seam->devices->list(device_type: "minut_sensor");
        $device_id = $devices[0]->device_id;

        $noise_thresholds = getMinutDeviceNoiseThresholds($seam, $device_id);
        $this->assertTrue(count($noise_thresholds) === 1);

        $seam->noise_sensors->noise_thresholds->create(
            device_id: $device_id,
            starts_daily_at: "20:00:00[America/Los_Angeles]",
            ends_daily_at: "08:00:00[America/Los_Angeles]",
            noise_threshold_decibels: 75
        );
        $noise_thresholds = getMinutDeviceNoiseThresholds($seam, $device_id);
        $this->assertTrue(count($noise_thresholds) === 2);

        $quiet_hours_threshold = array_filter($noise_thresholds, function (
            $nt
        ) {
            return $nt->name !== "builtin_normal_hours";
        });
        $quiet_hours_threshold_id = reset($quiet_hours_threshold)
            ->noise_threshold_id;

        $devices = $seam->noise_sensors->noise_thresholds->delete(
            device_id: $device_id,
            noise_threshold_id: $quiet_hours_threshold_id
        );
        $noise_thresholds = getMinutDeviceNoiseThresholds($seam, $device_id);
        $this->assertTrue(count($noise_thresholds) === 1);

        $normal_hours_threshold = array_filter($noise_thresholds, function (
            $nt
        ) {
            return $nt->name === "builtin_normal_hours";
        });
        $normal_hours_threshold_id = reset($normal_hours_threshold)
            ->noise_threshold_id;

        $seam->noise_sensors->noise_thresholds->update(
            device_id: $device_id,
            noise_threshold_id: $normal_hours_threshold_id,
            noise_threshold_decibels: 80
        );
        $updated_noise_threshold = $seam->noise_sensors->noise_thresholds->get(
            noise_threshold_id: $normal_hours_threshold_id
        );

        $this->assertTrue(
            $updated_noise_threshold->noise_threshold_decibels == 80
        );
    }
}
