<?php

namespace Seam\Resources {
    /**
     * Represents a thermostat daily program, consisting of a set of periods, each of which has a starting time and the key that identifies the climate preset to apply at the starting time.
     */
    class ThermostatDailyProgram
    {
        public static function from_json(
            mixed $json,
        ): ThermostatDailyProgram|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                name: $json->name ?? null,
                periods: array_map(
                    fn($p) => ThermostatDailyProgram\Periods::from_json($p),
                    $json->periods ?? [],
                ),
                thermostat_daily_program_id: $json->thermostat_daily_program_id ??
                    null,
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the thermostat daily program was created.
             */
            public string|null $created_at,
            /**
             * ID of the thermostat device on which the thermostat daily program is configured.
             */
            public string|null $device_id,
            /**
             * User-friendly name to identify the thermostat daily program.
             */
            public string|null $name,
            /**
             * Array of thermostat daily program periods.
             */
            public array $periods,
            /**
             * ID of the thermostat daily program.
             */
            public string|null $thermostat_daily_program_id,
            /**
             * ID of the workspace that contains the thermostat daily program.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\ThermostatDailyProgram {
    /**
     * Array of thermostat daily program periods.
     */
    class Periods
    {
        public static function from_json(mixed $json): Periods|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                climate_preset_key: $json->climate_preset_key ?? null,
                starts_at_time: $json->starts_at_time ?? null,
            );
        }

        public function __construct(
            /**
             * Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to activate at the `starts_at_time`.
             */
            public string|null $climate_preset_key,
            /**
             * Time at which the thermostat daily program period starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at_time,
        ) {}
    }
}
