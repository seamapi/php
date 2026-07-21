<?php

namespace Seam\Objects;

class DeviceGenieMetadata
{
    public static function from_json(mixed $json): DeviceGenieMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name ?? null,
            door_name: $json->door_name ?? null,
        );
    }

    public function __construct(
        public string|null $device_name,
        public string|null $door_name,
    ) {}
}
