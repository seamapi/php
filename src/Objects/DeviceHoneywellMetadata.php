<?php

namespace Seam\Objects;

class DeviceHoneywellMetadata
{
    
    public static function from_json(mixed $json): DeviceHoneywellMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            honeywell_device_id: $json->honeywell_device_id,
            device_name: $json->device_name,
        );
    }
  

    
    public function __construct(
        public string $honeywell_device_id,
        public string $device_name,
    ) {
    }
  
}
