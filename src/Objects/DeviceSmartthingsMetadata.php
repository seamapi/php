<?php

namespace Seam\Objects;

class DeviceSmartthingsMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceSmartthingsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            location_id: $json->location_id ?? null,
            model: $json->model ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public string|null $location_id,
        public string|null $model,
    ) {}
}
