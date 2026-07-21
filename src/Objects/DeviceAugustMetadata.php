<?php

namespace Seam\Objects;

class DeviceAugustMetadata
{
    public static function from_json(mixed $json): DeviceAugustMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            has_keypad: $json->has_keypad ?? null,
            house_id: $json->house_id ?? null,
            house_name: $json->house_name ?? null,
            keypad_battery_level: $json->keypad_battery_level ?? null,
            lock_id: $json->lock_id ?? null,
            lock_name: $json->lock_name ?? null,
            model: $json->model ?? null,
        );
    }

    public function __construct(
        public bool|null $has_keypad,
        public string|null $house_id,
        public string|null $house_name,
        public string|null $keypad_battery_level,
        public string|null $lock_id,
        public string|null $lock_name,
        public string|null $model,
    ) {}
}
