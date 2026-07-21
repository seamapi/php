<?php

namespace Seam\Objects;

class DeviceKwiksetMetadata
{
    public static function from_json(mixed $json): DeviceKwiksetMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            model_number: $json->model_number ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public string|null $model_number,
    ) {}
}
