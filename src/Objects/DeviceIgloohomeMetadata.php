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
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            is_accessory_keypad_linked_to_bridge: $json->is_accessory_keypad_linked_to_bridge ??
                null,
            keypad_id: $json->keypad_id ?? null,
        );
    }

    public function __construct(
        public string|null $bridge_id,
        public string|null $bridge_name,
        public string|null $device_id,
        public string|null $device_name,
        public bool|null $is_accessory_keypad_linked_to_bridge,
        public string|null $keypad_id,
    ) {}
}
