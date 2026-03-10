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
            device_id: $json->device_id,
            device_name: $json->device_name,
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_name,
    ) {}
}
