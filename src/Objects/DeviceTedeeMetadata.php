<?php

namespace Seam\Objects;

class DeviceTedeeMetadata
{
    
    public static function from_json(mixed $json): DeviceTedeeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            serial_number: $json->serial_number,
            device_name: $json->device_name,
            device_model: $json->device_model,
            bridge_id: $json->bridge_id,
            bridge_name: $json->bridge_name,
        );
    }
  

    
    public function __construct(
        public int $device_id,
        public string $serial_number,
        public string $device_name,
        public string $device_model,
        public int $bridge_id,
        public string $bridge_name,
    ) {
    }
  
}
