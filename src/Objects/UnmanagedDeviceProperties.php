<?php

namespace Seam\Objects;

class UnmanagedDeviceProperties
{
    public static function from_json(
        mixed $json
    ): UnmanagedDeviceProperties|null {
        if (!$json) {
            return null;
        }
        return new self(
            accessory_keypad: isset($json->accessory_keypad)
                ? UnmanagedDeviceAccessoryKeypad::from_json(
                    $json->accessory_keypad
                )
                : null,
            battery: isset($json->battery)
                ? UnmanagedDeviceBattery::from_json($json->battery)
                : null,
            battery_level: $json->battery_level ?? null,
            image_alt_text: $json->image_alt_text ?? null,
            image_url: $json->image_url ?? null,
            manufacturer: $json->manufacturer ?? null,
            model: UnmanagedDeviceModel::from_json($json->model),
            name: $json->name,
            offline_access_codes_enabled: $json->offline_access_codes_enabled ??
                null,
            online: $json->online,
            online_access_codes_enabled: $json->online_access_codes_enabled ??
                null
        );
    }

    public function __construct(
        public UnmanagedDeviceAccessoryKeypad|null $accessory_keypad,
        public UnmanagedDeviceBattery|null $battery,
        public float|null $battery_level,
        public string|null $image_alt_text,
        public string|null $image_url,
        public string|null $manufacturer,
        public UnmanagedDeviceModel $model,
        public string $name,
        public bool|null $offline_access_codes_enabled,
        public bool $online,
        public bool|null $online_access_codes_enabled
    ) {
    }
}
