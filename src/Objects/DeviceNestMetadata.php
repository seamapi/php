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
            device_custom_name: $json->device_custom_name ?? null,
            device_name: $json->device_name ?? null,
            display_name: $json->display_name ?? null,
            nest_device_id: $json->nest_device_id ?? null,
        );
    }

    public function __construct(
        public string|null $device_custom_name,
        public string|null $device_name,
        public string|null $display_name,
        public string|null $nest_device_id,
    ) {}
}
