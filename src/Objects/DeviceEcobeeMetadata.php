<?php

namespace Seam\Objects;

class DeviceEcobeeMetadata
{
    
    public static function from_json(mixed $json): DeviceEcobeeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            ecobee_device_id: $json->ecobee_device_id,
            device_name: $json->device_name,
        );
    }
  

    
    public function __construct(
        public string $ecobee_device_id,
        public string $device_name,
    ) {
    }
  
}
