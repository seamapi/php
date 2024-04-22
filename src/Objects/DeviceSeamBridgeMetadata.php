<?php

namespace Seam\Objects;

class DeviceSeamBridgeMetadata
{
    public static function from_json(mixed $json): DeviceSeamBridgeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_num: $json->device_num,
            name: $json->name,
            unlock_method: $json->unlock_method ?? null
        );
    }

    public function __construct(
        public float $device_num,
        public string $name,
        public string|null $unlock_method
    ) {
    }
}
