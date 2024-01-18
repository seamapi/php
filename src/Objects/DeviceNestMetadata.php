<?php

namespace Seam\Objects;

class DeviceNestMetadata
{
    
    public static function from_json(mixed $json): DeviceNestMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            nest_device_id: $json->nest_device_id,
            device_name: $json->device_name,
            custom_name: $json->custom_name,
        );
    }
  

    
    public function __construct(
        public string $nest_device_id,
        public string $device_name,
        public string $custom_name,
    ) {
    }
  
}
