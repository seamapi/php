<?php

namespace Seam\Objects;

class UnmanagedDeviceModel
{
    
    public static function from_json(mixed $json): UnmanagedDeviceModel|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            can_connect_accessory_keypad: $json->can_connect_accessory_keypad ?? null,
            display_name: $json->display_name,
            manufacturer_display_name: $json->manufacturer_display_name,
            has_built_in_keypad: $json->has_built_in_keypad ?? null,
            offline_access_codes_supported: $json->offline_access_codes_supported ?? null,
            online_access_codes_supported: $json->online_access_codes_supported ?? null,
            accessory_keypad_supported: $json->accessory_keypad_supported ?? null,
        );
    }
  

    
    public function __construct(
        public bool | null $can_connect_accessory_keypad,
        public string $display_name,
        public string $manufacturer_display_name,
        public bool | null $has_built_in_keypad,
        public bool | null $offline_access_codes_supported,
        public bool | null $online_access_codes_supported,
        public bool | null $accessory_keypad_supported,
    ) {
    }
  
}
