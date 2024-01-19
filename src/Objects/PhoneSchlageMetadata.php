<?php

namespace Seam\Objects;

class PhoneSchlageMetadata
{
    
    public static function from_json(mixed $json): PhoneSchlageMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            access_code_length: $json->access_code_length,
            model: $json->model ?? null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_name,
        public float $access_code_length,
        public string | null $model,
    ) {
    }
  
}
