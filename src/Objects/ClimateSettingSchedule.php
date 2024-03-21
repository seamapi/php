<?php

namespace Seam\Objects;

class ClimateSettingSchedule
{
    
    public static function from_json(mixed $json): ClimateSettingSchedule|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            climate_setting_schedule_id: $json->climate_setting_schedule_id,
            schedule_type: $json->schedule_type,
            device_id: $json->device_id,
            name: $json->name ?? null,
            schedule_starts_at: $json->schedule_starts_at,
            schedule_ends_at: $json->schedule_ends_at,
            created_at: $json->created_at,
            errors: $json->errors ?? null,
            automatic_heating_enabled: $json->automatic_heating_enabled ?? null,
            automatic_cooling_enabled: $json->automatic_cooling_enabled ?? null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ?? null,
            manual_override_allowed: $json->manual_override_allowed ?? null,
        );
    }
  

    
    public function __construct(
        public string $climate_setting_schedule_id,
        public string $schedule_type,
        public string $device_id,
        public string | null $name,
        public string $schedule_starts_at,
        public string $schedule_ends_at,
        public string $created_at,
        public mixed $errors,
        public bool | null $automatic_heating_enabled,
        public bool | null $automatic_cooling_enabled,
        public string | null $hvac_mode_setting,
        public int | null $cooling_set_point_celsius,
        public int | null $heating_set_point_celsius,
        public int | null $cooling_set_point_fahrenheit,
        public int | null $heating_set_point_fahrenheit,
        public bool | null $manual_override_allowed,
    ) {
    }
  
}
