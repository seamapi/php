<?php

namespace Seam\Objects;

class DeviceActiveThermostatSchedule
{
    public static function from_json(
        mixed $json,
    ): DeviceActiveThermostatSchedule|null {
        if (!$json) {
            return null;
        }
        return new self(
            climate_preset_key: $json->climate_preset_key ?? null,
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => DeviceErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_override_allowed: $json->is_override_allowed ?? null,
            max_override_period_minutes: $json->max_override_period_minutes ??
                null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
            thermostat_schedule_id: $json->thermostat_schedule_id ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $climate_preset_key,
        public string|null $created_at,
        public string|null $device_id,
        public string|null $ends_at,
        public array $errors,
        public bool|null $is_override_allowed,
        public int|null $max_override_period_minutes,
        public string|null $name,
        public string|null $starts_at,
        public string|null $thermostat_schedule_id,
        public string|null $workspace_id,
    ) {}
}
