<?php

namespace Seam\Resources {
    /**
     * Represents a [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) that activates a configured [climate preset](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-climate-presets) on a [thermostat](https://www.seam.co/docs/capability-guides/thermostats) at a specified starting time and deactivates the climate preset at a specified ending time.
     */
    class ThermostatSchedule
    {
        public static function from_json(mixed $json): ThermostatSchedule|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                climate_preset_key: $json->climate_preset_key ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ThermostatSchedule\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
                thermostat_schedule_id: $json->thermostat_schedule_id ?? null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ThermostatSchedule\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                is_override_allowed: $json->is_override_allowed ?? null,
                max_override_period_minutes: $json->max_override_period_minutes ??
                    null,
            );
        }

        public function __construct(
            /**
             * Key of the [climate preset](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $climate_preset_key,
            /**
             * Date and time at which the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) was created.
             */
            public string|null $created_at,
            /**
             * ID of the desired [thermostat](https://www.seam.co/docs/capability-guides/thermostats) device.
             */
            public string|null $device_id,
            /**
             * Date and time at which the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $ends_at,
            /**
             * Errors associated with the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             *
             * @var list<\Seam\Resources\ThermostatSchedule\Errors>
             */
            public array $errors,
            /**
             * User-friendly name to identify the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $name,
            /**
             * Date and time at which the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at,
            /**
             * ID of the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $thermostat_schedule_id,
            /**
             * Warnings associated with the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             *
             * @var list<\Seam\Resources\ThermostatSchedule\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the thermostat schedule.
             */
            public string|null $workspace_id,
            /**
             * Indicates whether a person at the thermostat can change the thermostat's settings after the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) starts.
             */
            public bool|null $is_override_allowed = null,
            /**
             * Number of minutes for which a person at the thermostat can change the thermostat's settings after the activation of the scheduled [climate preset](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-climate-presets). See also [Specifying Manual Override Permissions](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
             */
            public int|null $max_override_period_minutes = null,
        ) {}
    }
}

namespace Seam\Resources\ThermostatSchedule {
    /**
     * Errors associated with the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules). Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\ThermostatSchedule\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\ThermostatSchedule\Warnings\WarningCode::CLIMATE_PRESET_ACTIVATION_MAY_FAIL
                    => \Seam\Resources\ThermostatSchedule\Warnings\ClimatePresetActivationMayFail::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    warning_code: $json->warning_code ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\ThermostatSchedule\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\ThermostatSchedule\Warnings {
    /**
     * Indicates that the [thermostat](https://www.seam.co/docs/capability-guides/thermostats) may fail to activate a climate preset when the [thermostat schedule](https://www.seam.co/docs/capability-guides/thermostats/creating-and-managing-thermostat-schedules) starts or ends. The warning message explains the cause. Currently present while the thermostat is offline and the next start or end of the thermostat schedule is less than an hour away; it clears automatically once the thermostat is back online or that time passes.
     */
    final class ClimatePresetActivationMayFail extends
        \Seam\Resources\ThermostatSchedule\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ClimatePresetActivationMayFail|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\ThermostatSchedule\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    enum WarningCode: string
    {
        case CLIMATE_PRESET_ACTIVATION_MAY_FAIL = "climate_preset_activation_may_fail";
    }
}
