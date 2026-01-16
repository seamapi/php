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
            device_provider_name: $json->device_provider_name,
            display_name: $json->display_name,
            provider_category: $json->provider_category,
            image_url: $json->image_url ?? null,
        );
    }

    public function __construct(
        public string $device_provider_name,
        public string $display_name,
        public string $provider_category,
        public string|null $image_url,
    ) {}
}
