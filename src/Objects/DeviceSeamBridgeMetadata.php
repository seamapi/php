<?php

namespace Seam\Objects;

class DeviceSeamBridgeMetadata
{
    
    public static function from_json(mixed $json): DeviceSeamBridgeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            unlock_method: $json->unlock_method ?? null,
            device_num: $json->device_num,
            name: $json->name,
        );
    }
  

    
    public function __construct(
        public string | null $unlock_method,
        public float $device_num,
        public string $name,
    ) {
    }
  
}
