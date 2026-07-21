<?php

namespace Seam\Objects;

class DeviceWirelessKeypads
{
    public static function from_json(mixed $json): DeviceWirelessKeypads|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            wireless_keypad_id: $json->wireless_keypad_id ?? null,
            wireless_keypad_name: $json->wireless_keypad_name ?? null,
        );
    }

    public function __construct(
        public float|null $wireless_keypad_id,
        public string|null $wireless_keypad_name,
    ) {}
}
