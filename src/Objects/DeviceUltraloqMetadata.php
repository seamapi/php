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
            device_type: $json->device_type,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_name,
        public string $device_type,
        public string|null $time_zone,
    ) {}
}
