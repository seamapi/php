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
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            created_at: $json->created_at,
            device_id: $json->device_id,
            errors: $json->errors ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            manual_override_allowed: $json->manual_override_allowed ?? null,
            name: $json->name ?? null,
            schedule_ends_at: $json->schedule_ends_at,
            schedule_starts_at: $json->schedule_starts_at,
            schedule_type: $json->schedule_type
        );
    }

    public function __construct(
        public string $climate_setting_schedule_id,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string $created_at,
        public string $device_id,
        public mixed $errors,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $manual_override_allowed,
        public string|null $name,
        public string $schedule_ends_at,
        public string $schedule_starts_at,
        public string $schedule_type
    ) {
    }
}
