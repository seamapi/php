<?php

namespace Seam\Objects;

class ThermostatDailyProgramPeriods
{
    public static function from_json(
        mixed $json,
    ): ThermostatDailyProgramPeriods|null {
        if (!$json) {
            return null;
        }
        return new self(
            climate_preset_key: $json->climate_preset_key ?? null,
            starts_at_time: $json->starts_at_time ?? null,
        );
    }

    public function __construct(
        public string|null $climate_preset_key,
        public string|null $starts_at_time,
    ) {}
}
