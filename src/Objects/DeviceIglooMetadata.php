<?php

namespace Seam\Objects;

class DeviceIglooMetadata
{
    
    public static function from_json(mixed $json): DeviceIglooMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            bridge_id: $json->bridge_id,
            model: $json->model ?? null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $bridge_id,
        public string | null $model,
    ) {
    }
  
}
