<?php

namespace Seam\Objects;

class DeviceUltraloqMetadata
{
    public static function from_json(mixed $json): DeviceUltraloqMetadata|null
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
