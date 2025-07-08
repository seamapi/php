<?php

namespace Seam\Objects;

class DeviceThermostatDailyPrograms
{
    public static function from_json(
        mixed $json
    ): DeviceThermostatDailyPrograms|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            device_id: $json->device_id,
            periods: array_map(
                fn($p) => DevicePeriods::from_json($p),
                $json->periods ?? []
            ),
            thermostat_daily_program_id: $json->thermostat_daily_program_id,
            workspace_id: $json->workspace_id,
            name: $json->name ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public string $device_id,
        public array $periods,
        public string $thermostat_daily_program_id,
        public string $workspace_id,
        public string|null $name
    ) {}
}
