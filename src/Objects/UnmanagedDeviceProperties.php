<?php

namespace Seam\Objects;

class UnmanagedDeviceProperties
{
    
    public static function from_json(mixed $json): UnmanagedDeviceProperties|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            name: $json->name,
            online: $json->online,
            manufacturer: $json->manufacturer ?? null,
            image_url: $json->image_url ?? null,
            image_alt_text: $json->image_alt_text ?? null,
            battery_level: $json->battery_level ?? null,
            battery: isset($json->battery) ? UnmanagedDeviceBattery::from_json($json->battery) : null,
            online_access_codes_enabled: $json->online_access_codes_enabled ?? null,
            offline_access_codes_enabled: $json->offline_access_codes_enabled ?? null,
            model: UnmanagedDeviceModel::from_json($json->model),
        );
    }
    
    public function __construct(
        public string $name,
        public bool $online,
        public string | null $manufacturer,
        public string | null $image_url,
        public string | null $image_alt_text,
        public float | null $battery_level,
        public UnmanagedDeviceBattery | null $battery,
        public bool | null $online_access_codes_enabled,
        public bool | null $offline_access_codes_enabled,
        public UnmanagedDeviceModel $model,
    ) {
    }

}
