<?php

namespace Seam\Resources {
    class DeviceProvider
    {
        public static function from_json(mixed $json): DeviceProvider|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                can_configure_auto_lock: $json->can_configure_auto_lock ?? null,
                can_hvac_cool: $json->can_hvac_cool ?? null,
                can_hvac_heat: $json->can_hvac_heat ?? null,
                can_hvac_heat_cool: $json->can_hvac_heat_cool ?? null,
                can_program_offline_access_codes: $json->can_program_offline_access_codes ??
                    null,
                can_program_online_access_codes: $json->can_program_online_access_codes ??
                    null,
                can_program_thermostat_programs_as_different_each_day: $json->can_program_thermostat_programs_as_different_each_day ??
                    null,
                can_program_thermostat_programs_as_same_each_day: $json->can_program_thermostat_programs_as_same_each_day ??
                    null,
                can_program_thermostat_programs_as_weekday_weekend: $json->can_program_thermostat_programs_as_weekday_weekend ??
                    null,
                can_remotely_lock: $json->can_remotely_lock ?? null,
                can_remotely_unlock: $json->can_remotely_unlock ?? null,
                can_run_thermostat_programs: $json->can_run_thermostat_programs ??
                    null,
                can_simulate_connection: $json->can_simulate_connection ?? null,
                can_simulate_disconnection: $json->can_simulate_disconnection ??
                    null,
                can_simulate_hub_connection: $json->can_simulate_hub_connection ??
                    null,
                can_simulate_hub_disconnection: $json->can_simulate_hub_disconnection ??
                    null,
                can_simulate_paid_subscription: $json->can_simulate_paid_subscription ??
                    null,
                can_simulate_removal: $json->can_simulate_removal ?? null,
                can_turn_off_hvac: $json->can_turn_off_hvac ?? null,
                can_unlock_with_code: $json->can_unlock_with_code ?? null,
                device_provider_name: $json->device_provider_name ?? null,
                display_name: $json->display_name ?? null,
                image_url: $json->image_url ?? null,
                provider_categories: $json->provider_categories ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the lock supports configuring automatic locking.
             */
            public bool|null $can_configure_auto_lock,
            /**
             * Indicates whether the thermostat supports cooling.
             */
            public bool|null $can_hvac_cool,
            /**
             * Indicates whether the thermostat supports heating.
             */
            public bool|null $can_hvac_heat,
            /**
             * Indicates whether the thermostat supports simultaneous heating and cooling.
             */
            public bool|null $can_hvac_heat_cool,
            /**
             * Indicates whether the device supports programming offline access codes.
             */
            public bool|null $can_program_offline_access_codes,
            /**
             * Indicates whether the device supports programming online access codes.
             */
            public bool|null $can_program_online_access_codes,
            /**
             * Indicates whether the thermostat supports different climate programs for each day of the week.
             */
            public bool|null $can_program_thermostat_programs_as_different_each_day,
            /**
             * Indicates whether the thermostat supports a single climate program applied to every day.
             */
            public bool|null $can_program_thermostat_programs_as_same_each_day,
            /**
             * Indicates whether the thermostat supports weekday/weekend climate programs.
             */
            public bool|null $can_program_thermostat_programs_as_weekday_weekend,
            /**
             * Indicates whether the device supports remote locking.
             */
            public bool|null $can_remotely_lock,
            /**
             * Indicates whether the device supports remote unlocking.
             */
            public bool|null $can_remotely_unlock,
            /**
             * Indicates whether the thermostat supports running climate programs.
             */
            public bool|null $can_run_thermostat_programs,
            /**
             * Indicates whether the device supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_connection,
            /**
             * Indicates whether the device supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_disconnection,
            /**
             * Indicates whether the hub supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_hub_connection,
            /**
             * Indicates whether the hub supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_hub_disconnection,
            /**
             * Indicates whether the device supports simulating a paid subscription in a sandbox.
             */
            public bool|null $can_simulate_paid_subscription,
            /**
             * Indicates whether the device supports simulating removal in a sandbox.
             */
            public bool|null $can_simulate_removal,
            /**
             * Indicates whether the thermostat can be turned off.
             */
            public bool|null $can_turn_off_hvac,
            /**
             * Indicates whether the lock supports unlocking with an access code.
             */
            public bool|null $can_unlock_with_code,
            /**
             * Name of the device provider.
             */
            public string|null $device_provider_name,
            /**
             * Display name for the device provider.
             */
            public string|null $display_name,
            /**
             * Image URL for the device provider.
             */
            public string|null $image_url,
            /**
             * List of provider categories to which the device provider belongs, such as `stable`, `consumer_smartlocks`, `thermostats`, and so on.
             */
            public array|null $provider_categories,
        ) {}
    }
}
