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
            display_name: $json->display_name ?? null,
            image_url: $json->image_url ?? null,
            manufacturer: $json->manufacturer ?? null,
        );
    }

    public function __construct(
        public string|null $display_name,
        public string|null $image_url,
        public string|null $manufacturer,
    ) {}
}
