<?php

namespace Seam\Objects;

class DeviceDeviceProvider
{
    public static function from_json(mixed $json): DeviceDeviceProvider|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_provider_name: $json->device_provider_name ?? null,
            display_name: $json->display_name ?? null,
            image_url: $json->image_url ?? null,
            provider_category: $json->provider_category ?? null,
        );
    }

    public function __construct(
        public string|null $device_provider_name,
        public string|null $display_name,
        public string|null $image_url,
        public string|null $provider_category,
    ) {}
}
