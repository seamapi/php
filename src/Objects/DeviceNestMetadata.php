<?php

namespace Seam\Objects;

class DeviceNestMetadata
{
    public static function from_json(mixed $json): DeviceNestMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_custom_name: $json->device_custom_name,
            device_name: $json->device_name,
            nest_device_id: $json->nest_device_id,
            display_name: $json->display_name ?? null
        );
    }

    public function __construct(
        public string $device_custom_name,
        public string $device_name,
        public string $nest_device_id,
        public string|null $display_name
    ) {
    }
}
