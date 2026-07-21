<?php

namespace Seam\Objects;

class DeviceMinutMetadata
{
    public static function from_json(mixed $json): DeviceMinutMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            latest_sensor_values: isset($json->latest_sensor_values)
                ? DeviceLatestSensorValues::from_json(
                    $json->latest_sensor_values,
                )
                : null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public DeviceLatestSensorValues|null $latest_sensor_values,
    ) {}
}
