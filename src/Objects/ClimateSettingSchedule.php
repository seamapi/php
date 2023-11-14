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
            climate_setting_schedule_id: $json->climate_setting_schedule_id ??
                null,
            schedule_type: $json->schedule_type ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            schedule_starts_at: $json->schedule_starts_at ?? null,
            schedule_ends_at: $json->schedule_ends_at ?? null,
            created_at: $json->created_at ?? null,
            automatic_heating_enabled: $json->automatic_heating_enabled ?? null,
            automatic_cooling_enabled: $json->automatic_cooling_enabled ?? null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            manual_override_allowed: $json->manual_override_allowed ?? null
        );
    }

    public function __construct(
        public string|null $climate_setting_schedule_id,
        public string|null $schedule_type,
        public string|null $device_id,
        public string|null $name,
        public string|null $schedule_starts_at,
        public string|null $schedule_ends_at,
        public string|null $created_at,
        public bool|null $automatic_heating_enabled,
        public bool|null $automatic_cooling_enabled,
        public string|null $hvac_mode_setting,
        public float|null $cooling_set_point_celsius,
        public float|null $heating_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public float|null $heating_set_point_fahrenheit,
        public bool|null $manual_override_allowed
    ) {
    }
}
