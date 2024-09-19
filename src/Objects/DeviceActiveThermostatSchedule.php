<?php

namespace Seam\Objects;

class DeviceActiveThermostatSchedule
{
    public static function from_json(
        mixed $json
    ): DeviceActiveThermostatSchedule|null {
        if (!$json) {
            return null;
        }
        return new self(
            climate_preset_key: $json->climate_preset_key,
            created_at: $json->created_at,
            device_id: $json->device_id,
            ends_at: $json->ends_at,
            errors: $json->errors ?? null,
            max_override_period_minutes: $json->max_override_period_minutes ??
                null,
            name: $json->name ?? null,
            starts_at: $json->starts_at,
            thermostat_schedule_id: $json->thermostat_schedule_id
        );
    }

    public function __construct(
        public string $climate_preset_key,
        public string $created_at,
        public string $device_id,
        public string $ends_at,
        public mixed $errors,
        public mixed $max_override_period_minutes,
        public string|null $name,
        public string $starts_at,
        public string $thermostat_schedule_id
    ) {
    }
}
