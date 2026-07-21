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
            bridge_id: $json->bridge_id ?? null,
            device_id: $json->device_id ?? null,
            model: $json->model ?? null,
        );
    }

    public function __construct(
        public string|null $bridge_id,
        public string|null $device_id,
        public string|null $model,
    ) {}
}
