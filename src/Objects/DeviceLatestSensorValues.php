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
            accelerometer_z: isset($json->accelerometer_z)
                ? DeviceAccelerometerZ::from_json($json->accelerometer_z)
                : null,
            humidity: isset($json->humidity)
                ? DeviceHumidity::from_json($json->humidity)
                : null,
            pressure: isset($json->pressure)
                ? DevicePressure::from_json($json->pressure)
                : null,
            sound: isset($json->sound)
                ? DeviceSound::from_json($json->sound)
                : null,
            temperature: isset($json->temperature)
                ? DeviceTemperature::from_json($json->temperature)
                : null,
        );
    }

    public function __construct(
        public DeviceAccelerometerZ|null $accelerometer_z,
        public DeviceHumidity|null $humidity,
        public DevicePressure|null $pressure,
        public DeviceSound|null $sound,
        public DeviceTemperature|null $temperature,
    ) {}
}
