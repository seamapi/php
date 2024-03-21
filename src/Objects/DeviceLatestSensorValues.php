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
            temperature: DeviceTemperature::from_json($json->temperature),
            sound: DeviceSound::from_json($json->sound),
            humidity: DeviceHumidity::from_json($json->humidity),
            pressure: DevicePressure::from_json($json->pressure),
            accelerometer_z: DeviceAccelerometerZ::from_json($json->accelerometer_z),
        );
    }
  

    
    public function __construct(
        public DeviceTemperature $temperature,
        public DeviceSound $sound,
        public DeviceHumidity $humidity,
        public DevicePressure $pressure,
        public DeviceAccelerometerZ $accelerometer_z,
    ) {
    }
  
}
