<?php

namespace Seam\Objects;

class UnmanagedDeviceAccessoryKeypad
{
    public static function from_json(
        mixed $json
    ): UnmanagedDeviceAccessoryKeypad|null {
        if (!$json) {
            return null;
        }
        return new self(
            is_connected: $json->is_connected,
            battery: isset($json->battery)
                ? UnmanagedDeviceBattery::from_json($json->battery)
                : null
        );
    }

    public function __construct(
        public bool $is_connected,
        public UnmanagedDeviceBattery|null $battery
    ) {
    }
}
