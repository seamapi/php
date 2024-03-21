<?php

namespace Seam\Objects;

class DeviceFourSuitesMetadata
{
    
    public static function from_json(mixed $json): DeviceFourSuitesMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            reclose_delay_in_seconds: $json->reclose_delay_in_seconds,
        );
    }
  

    
    public function __construct(
        public int $device_id,
        public string $device_name,
        public int $reclose_delay_in_seconds,
    ) {
    }
  
}
