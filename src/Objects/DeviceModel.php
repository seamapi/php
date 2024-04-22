<?php

namespace Seam\Objects;

class DeviceModel
{
    public static function from_json(mixed $json): DeviceModel|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            accessory_keypad_supported: $json->accessory_keypad_supported ??
                null,
            can_connect_accessory_keypad: $json->can_connect_accessory_keypad ??
                null,
            display_name: $json->display_name,
            has_built_in_keypad: $json->has_built_in_keypad ?? null,
            manufacturer_display_name: $json->manufacturer_display_name,
            offline_access_codes_supported: $json->offline_access_codes_supported ??
                null,
            online_access_codes_supported: $json->online_access_codes_supported ??
                null
        );
    }

    public function __construct(
        public bool|null $accessory_keypad_supported,
        public bool|null $can_connect_accessory_keypad,
        public string $display_name,
        public bool|null $has_built_in_keypad,
        public string $manufacturer_display_name,
        public bool|null $offline_access_codes_supported,
        public bool|null $online_access_codes_supported
    ) {
    }
}
