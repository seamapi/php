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
            bridge_id: $json->bridge_id,
            device_id: $json->device_id,
            model: $json->model ?? null
        );
    }

    public function __construct(
        public string $bridge_id,
        public string $device_id,
        public string|null $model
    ) {
    }
}
