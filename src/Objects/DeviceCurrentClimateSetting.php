<?php

namespace Seam\Objects;

class DeviceCurrentClimateSetting
{
    public static function from_json(
        mixed $json
    ): DeviceCurrentClimateSetting|null {
        if (!$json) {
            return null;
        }
        return new self(
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting,
            manual_override_allowed: $json->manual_override_allowed
        );
    }

    public function __construct(
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string $hvac_mode_setting,
        public bool $manual_override_allowed
    ) {
    }
}
