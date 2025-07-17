<?php

namespace Seam\Objects;

class ThermostatSchedule
{
    public static function from_json(mixed $json): ThermostatSchedule|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            climate_preset_key: $json->climate_preset_key,
            created_at: $json->created_at,
            device_id: $json->device_id,
            ends_at: $json->ends_at,
            errors: array_map(
                fn($e) => ThermostatScheduleErrors::from_json($e),
                $json->errors ?? [],
            ),
            starts_at: $json->starts_at,
            thermostat_schedule_id: $json->thermostat_schedule_id,
            workspace_id: $json->workspace_id,
            is_override_allowed: $json->is_override_allowed ?? null,
            name: $json->name ?? null,
            max_override_period_minutes: $json->max_override_period_minutes ??
                null,
        );
    }

    public function __construct(
        public string $climate_preset_key,
        public string $created_at,
        public string $device_id,
        public string $ends_at,
        public array $errors,
        public string $starts_at,
        public string $thermostat_schedule_id,
        public string $workspace_id,
        public bool|null $is_override_allowed,
        public string|null $name,
        public mixed $max_override_period_minutes,
    ) {}
}
