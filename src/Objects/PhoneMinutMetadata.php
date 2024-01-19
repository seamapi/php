<?php

namespace Seam\Objects;

class PhoneMinutMetadata
{
    
    public static function from_json(mixed $json): PhoneMinutMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            latest_sensor_values: PhoneLatestSensorValues::from_json($json->latest_sensor_values),
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_name,
        public PhoneLatestSensorValues $latest_sensor_values,
    ) {
    }
  
}
