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
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            device_type: $json->device_type ?? null,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public string|null $device_type,
        public string|null $time_zone,
    ) {}
}
