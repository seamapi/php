<?php

namespace Seam\Objects;

class DeviceRingMetadata
{
    public static function from_json(mixed $json): DeviceRingMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
    ) {}
}
