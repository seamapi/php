<?php

namespace Seam\Objects;

class DeviceSchlageMetadata
{
    public static function from_json(mixed $json): DeviceSchlageMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_code_length: $json->access_code_length ?? null,
            device_id: $json->device_id,
            device_name: $json->device_name,
            model: $json->model ?? null
        );
    }

    public function __construct(
        public float|null $access_code_length,
        public string $device_id,
        public string $device_name,
        public string|null $model
    ) {
    }
}
