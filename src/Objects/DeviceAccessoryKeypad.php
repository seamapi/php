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
            battery: isset($json->battery)
                ? DeviceBattery::from_json($json->battery)
                : null,
            is_connected: $json->is_connected ?? null,
        );
    }

    public function __construct(
        public DeviceBattery|null $battery,
        public bool|null $is_connected,
    ) {}
}
