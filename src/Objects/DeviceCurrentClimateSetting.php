<?php

namespace Seam\Objects;

class DeviceCurrentClimateSetting
{
    
    public static function from_json(mixed $json): DeviceCurrentClimateSetting|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            automatic_heating_enabled: $json->automatic_heating_enabled,
            automatic_cooling_enabled: $json->automatic_cooling_enabled,
            hvac_mode_setting: $json->hvac_mode_setting,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ?? null,
            manual_override_allowed: $json->manual_override_allowed,
        );
    }
  

    
    public function __construct(
        public bool $automatic_heating_enabled,
        public bool $automatic_cooling_enabled,
        public string $hvac_mode_setting,
        public int | null $cooling_set_point_celsius,
        public int | null $heating_set_point_celsius,
        public int | null $cooling_set_point_fahrenheit,
        public int | null $heating_set_point_fahrenheit,
        public bool $manual_override_allowed,
    ) {
    }
  
}
