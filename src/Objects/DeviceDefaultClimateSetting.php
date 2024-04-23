<?php

namespace Seam\Objects;

class DeviceDefaultClimateSetting
{
    
    public static function from_json(mixed $json): DeviceDefaultClimateSetting|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            automatic_cooling_enabled: $json->automatic_cooling_enabled,
            automatic_heating_enabled: $json->automatic_heating_enabled,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ?? null,
            hvac_mode_setting: $json->hvac_mode_setting,
            manual_override_allowed: $json->manual_override_allowed,
        );
    }
  

    
    public function __construct(
        public bool $automatic_cooling_enabled,
        public bool $automatic_heating_enabled,
        public float | null $cooling_set_point_celsius,
        public float | null $cooling_set_point_fahrenheit,
        public float | null $heating_set_point_celsius,
        public float | null $heating_set_point_fahrenheit,
        public string $hvac_mode_setting,
        public bool $manual_override_allowed,
    ) {
    }
  
}
