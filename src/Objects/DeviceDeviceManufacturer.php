<?php

namespace Seam\Objects;

class DeviceDeviceManufacturer
{
    public static function from_json(mixed $json): DeviceDeviceManufacturer|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name,
            manufacturer: $json->manufacturer,
            image_url: $json->image_url ?? null,
        );
    }

    public function __construct(
        public string $display_name,
        public string $manufacturer,
        public string|null $image_url,
    ) {}
}
