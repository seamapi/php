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
            custom_name: $json->custom_name,
            device_name: $json->device_name,
            display_name: $json->display_name ?? null,
            nest_device_id: $json->nest_device_id
        );
    }

    public function __construct(
        public string $custom_name,
        public string $device_name,
        public string|null $display_name,
        public string $nest_device_id
    ) {
    }
}
