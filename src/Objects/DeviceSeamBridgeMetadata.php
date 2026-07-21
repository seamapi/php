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
            device_num: $json->device_num ?? null,
            name: $json->name ?? null,
            unlock_method: $json->unlock_method ?? null,
        );
    }

    public function __construct(
        public float|null $device_num,
        public string|null $name,
        public string|null $unlock_method,
    ) {}
}
