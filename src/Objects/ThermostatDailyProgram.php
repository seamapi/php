<?php

namespace Seam\Objects;

class ThermostatDailyProgram
{
    public static function from_json(mixed $json): ThermostatDailyProgram|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            periods: array_map(
                fn($p) => ThermostatDailyProgramPeriods::from_json($p),
                $json->periods ?? [],
            ),
            thermostat_daily_program_id: $json->thermostat_daily_program_id ??
                null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $device_id,
        public string|null $name,
        public array $periods,
        public string|null $thermostat_daily_program_id,
        public string|null $workspace_id,
    ) {}
}
