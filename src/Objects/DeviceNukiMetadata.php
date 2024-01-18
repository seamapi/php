<?php

namespace Seam\Objects;

class DeviceNukiMetadata
{
    
    public static function from_json(mixed $json): DeviceNukiMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            keypad_battery_critical: $json->keypad_battery_critical ?? null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_name,
        public bool | null $keypad_battery_critical,
    ) {
    }
  
}
