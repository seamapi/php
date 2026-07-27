<?php

namespace Seam\Objects;

class DeviceAqaraMetadata
{
    public static function from_json(mixed $json): DeviceAqaraMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name ?? null,
            did: $json->did ?? null,
            firmware_version: $json->firmware_version ?? null,
            model: $json->model ?? null,
            model_type: $json->model_type ?? null,
            parent_did: $json->parent_did ?? null,
            position_id: $json->position_id ?? null,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $device_name,
        public string|null $did,
        public string|null $firmware_version,
        public string|null $model,
        public float|null $model_type,
        public string|null $parent_did,
        public string|null $position_id,
        public string|null $time_zone,
    ) {}
}
