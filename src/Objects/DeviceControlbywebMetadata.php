<?php

namespace Seam\Objects;

class DeviceControlbywebMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceControlbywebMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            relay_name: $json->relay_name ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public string|null $relay_name,
    ) {}
}
