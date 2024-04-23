<?php

namespace Seam\Objects;

class DeviceLatestSensorValues
{
    public static function from_json(mixed $json): DeviceLatestSensorValues|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            accelerometer_z: DeviceAccelerometerZ::from_json(
                $json->accelerometer_z
            ),
            humidity: DeviceHumidity::from_json($json->humidity),
            pressure: DevicePressure::from_json($json->pressure),
            sound: DeviceSound::from_json($json->sound),
            temperature: DeviceTemperature::from_json($json->temperature)
        );
    }

    public function __construct(
        public DeviceAccelerometerZ $accelerometer_z,
        public DeviceHumidity $humidity,
        public DevicePressure $pressure,
        public DeviceSound $sound,
        public DeviceTemperature $temperature
    ) {
    }
}
