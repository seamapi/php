<?php

namespace Seam\Objects;

class DeviceHubitatMetadata
{
    public static function from_json(mixed $json): DeviceHubitatMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_label: $json->device_label,
            device_name: $json->device_name
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_label,
        public string $device_name
    ) {
    }
}
