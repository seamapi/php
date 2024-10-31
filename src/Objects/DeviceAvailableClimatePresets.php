<?php

namespace Seam\Objects;

class DeviceAvailableClimatePresets
{
    public static function from_json(
        mixed $json
    ): DeviceAvailableClimatePresets|null {
        if (!$json) {
            return null;
        }
        return new self(
            can_delete: $json->can_delete,
            can_edit: $json->can_edit,
            climate_preset_key: $json->climate_preset_key,
            display_name: $json->display_name,
            manual_override_allowed: $json->manual_override_allowed,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            name: $json->name ?? null
        );
    }

    public function __construct(
        public bool $can_delete,
        public bool $can_edit,
        public string $climate_preset_key,
        public string $display_name,
        public bool $manual_override_allowed,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public string|null $name
    ) {
    }
}
