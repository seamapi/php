<?php

namespace Seam\Objects;

class PhoneKwiksetMetadata
{
    
    public static function from_json(mixed $json): PhoneKwiksetMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            model_number: $json->model_number,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_name,
        public string $model_number,
    ) {
    }
  
}
