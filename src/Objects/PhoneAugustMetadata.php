<?php

namespace Seam\Objects;

class PhoneAugustMetadata
{
    
    public static function from_json(mixed $json): PhoneAugustMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            lock_id: $json->lock_id,
            lock_name: $json->lock_name,
            house_name: $json->house_name,
            has_keypad: $json->has_keypad,
            keypad_battery_level: $json->keypad_battery_level ?? null,
            model: $json->model ?? null,
            house_id: $json->house_id ?? null,
        );
    }
  

    
    public function __construct(
        public string $lock_id,
        public string $lock_name,
        public string $house_name,
        public bool $has_keypad,
        public string | null $keypad_battery_level,
        public string | null $model,
        public string | null $house_id,
    ) {
    }
  
}
