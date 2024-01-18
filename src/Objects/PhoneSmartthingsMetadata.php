<?php

namespace Seam\Objects;

class PhoneSmartthingsMetadata
{
    
    public static function from_json(mixed $json): PhoneSmartthingsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            model: $json->model ?? null,
            location_id: $json->location_id ?? null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_name,
        public string | null $model,
        public string | null $location_id,
    ) {
    }
  
}
