<?php

namespace Seam\Objects;

class PhoneGenieMetadata
{
    
    public static function from_json(mixed $json): PhoneGenieMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name,
            door_name: $json->door_name,
        );
    }
  

    
    public function __construct(
        public string $device_name,
        public string $door_name,
    ) {
    }
  
}
