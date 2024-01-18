<?php

namespace Seam\Objects;

class PhoneLatestSensorValues
{
    
    public static function from_json(mixed $json): PhoneLatestSensorValues|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            temperature: PhoneTemperature::from_json($json->temperature),
            sound: PhoneSound::from_json($json->sound),
            humidity: PhoneHumidity::from_json($json->humidity),
            pressure: PhonePressure::from_json($json->pressure),
            accelerometer_z: PhoneAccelerometerZ::from_json($json->accelerometer_z),
        );
    }
  

    
    public function __construct(
        public PhoneTemperature $temperature,
        public PhoneSound $sound,
        public PhoneHumidity $humidity,
        public PhonePressure $pressure,
        public PhoneAccelerometerZ $accelerometer_z,
    ) {
    }
  
}
