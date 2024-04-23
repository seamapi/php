<?php

namespace Seam\Objects;

class DeviceIgloohomeMetadata
{
    public static function from_json(mixed $json): DeviceIgloohomeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            bridge_id: $json->bridge_id ?? null,
            bridge_name: $json->bridge_name ?? null,
            device_id: $json->device_id,
            device_name: $json->device_name,
            keypad_id: $json->keypad_id ?? null
        );
    }

    public function __construct(
        public string|null $bridge_id,
        public string|null $bridge_name,
        public string $device_id,
        public string $device_name,
        public string|null $keypad_id
    ) {
    }
}
