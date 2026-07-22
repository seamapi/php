<?php

namespace Seam\Objects;

class UnmanagedDeviceAccessoryKeypad
{
    public static function from_json(
        mixed $json,
    ): UnmanagedDeviceAccessoryKeypad|null {
        if (!$json) {
            return null;
        }
        return new self(
            battery: isset($json->battery)
                ? UnmanagedDeviceBattery::from_json($json->battery)
                : null,
            is_connected: $json->is_connected ?? null,
        );
    }

    public function __construct(
        public UnmanagedDeviceBattery|null $battery,
        public bool|null $is_connected,
    ) {}
}
