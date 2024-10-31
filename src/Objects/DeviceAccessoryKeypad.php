<?php

namespace Seam\Objects;

class DeviceAccessoryKeypad
{
    public static function from_json(mixed $json): DeviceAccessoryKeypad|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            is_connected: $json->is_connected,
            battery: isset($json->battery)
                ? DeviceBattery::from_json($json->battery)
                : null
        );
    }

    public function __construct(
        public bool $is_connected,
        public DeviceBattery|null $battery
    ) {
    }
}
