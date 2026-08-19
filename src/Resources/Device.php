<?php

namespace Seam\Resources {
    /**
     * Represents a [device](https://docs.seam.co/core-concepts/devices) that has been connected to Seam.
     */
    class Device
    {
        public static function from_json(mixed $json): Device|null
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
                capabilities_supported: $json->capabilities_supported ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                custom_metadata: $json->custom_metadata ?? null,
                device_id: $json->device_id ?? null,
                device_manufacturer: isset($json->device_manufacturer)
                    ? Device\DeviceManufacturer::from_json(
                        $json->device_manufacturer,
                    )
                    : null,
                device_provider: isset($json->device_provider)
                    ? Device\DeviceProvider::from_json($json->device_provider)
                    : null,
                device_type: $json->device_type ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => Device\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                location: isset($json->location)
                    ? Device\Location::from_json($json->location)
                    : null,
                nickname: $json->nickname ?? null,
                properties: isset($json->properties)
                    ? Device\Properties::from_json($json->properties)
                    : null,
                space_ids: $json->space_ids ?? null,
                warnings: array_map(
                    fn($w) => Device\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the lock supports configuring automatic locking.
             */
            public bool|null $can_configure_auto_lock = null,
            /**
             * Indicates whether the thermostat supports cooling.
             */
            public bool|null $can_hvac_cool = null,
            /**
             * Indicates whether the thermostat supports heating.
             */
            public bool|null $can_hvac_heat = null,
            /**
             * Indicates whether the thermostat supports simultaneous heating and cooling.
             */
            public bool|null $can_hvac_heat_cool = null,
            /**
             * Indicates whether the device supports programming offline access codes.
             */
            public bool|null $can_program_offline_access_codes = null,
            /**
             * Indicates whether the device supports programming online access codes.
             */
            public bool|null $can_program_online_access_codes = null,
            /**
             * Indicates whether the thermostat supports different climate programs for each day of the week.
             */
            public bool|null $can_program_thermostat_programs_as_different_each_day = null,
            /**
             * Indicates whether the thermostat supports a single climate program applied to every day.
             */
            public bool|null $can_program_thermostat_programs_as_same_each_day = null,
            /**
             * Indicates whether the thermostat supports weekday/weekend climate programs.
             */
            public bool|null $can_program_thermostat_programs_as_weekday_weekend = null,
            /**
             * Indicates whether the device supports remote locking.
             */
            public bool|null $can_remotely_lock = null,
            /**
             * Indicates whether the device supports remote unlocking.
             */
            public bool|null $can_remotely_unlock = null,
            /**
             * Indicates whether the thermostat supports running climate programs.
             */
            public bool|null $can_run_thermostat_programs = null,
            /**
             * Indicates whether the device supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_connection = null,
            /**
             * Indicates whether the device supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_disconnection = null,
            /**
             * Indicates whether the hub supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_hub_connection = null,
            /**
             * Indicates whether the hub supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_hub_disconnection = null,
            /**
             * Indicates whether the device supports simulating a paid subscription in a sandbox.
             */
            public bool|null $can_simulate_paid_subscription = null,
            /**
             * Indicates whether the device supports simulating removal in a sandbox.
             */
            public bool|null $can_simulate_removal = null,
            /**
             * Indicates whether the thermostat can be turned off.
             */
            public bool|null $can_turn_off_hvac = null,
            /**
             * Indicates whether the lock supports unlocking with an access code.
             */
            public bool|null $can_unlock_with_code = null,
            /**
             * Collection of capabilities that the device supports when connected to Seam. Values are `access_code`, which indicates that the device can manage and utilize digital PIN codes for secure access; `lock`, which indicates that the device controls a door locking mechanism, enabling the remote opening and closing of doors and other entry points; `noise_detection`, which indicates that the device supports monitoring and responding to ambient noise levels; `thermostat`, which indicates that the device can regulate and adjust indoor temperatures; `battery`, which indicates that the device can manage battery life and health; and `phone`, which indicates that the device is a mobile device, such as a smartphone. **Important:** Superseded by [capability flags](https://docs.seam.co/capability-guides/device-and-system-capabilities#capability-flags).
             */
            public array|null $capabilities_supported,
            /**
             * Unique identifier for the account associated with the device.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the device object was created.
             */
            public string|null $created_at,
            /**
             * Set of key:value pairs. Adding custom metadata to a resource, such as a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews/attaching-custom-data-to-the-connect-webview), [connected account](https://docs.seam.co/core-concepts/connected-accounts/adding-custom-metadata-to-a-connected-account), or [device](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device), enables you to store custom information, like customer details or internal IDs from your application.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $custom_metadata,
            /**
             * ID of the device.
             */
            public string|null $device_id,
            /**
             * Manufacturer of the device. Represents the hardware brand, which may differ from the provider.
             */
            public Device\DeviceManufacturer|null $device_manufacturer = null,
            /**
             * Provider of the device. Represents the third-party service through which the device is controlled.
             */
            public Device\DeviceProvider|null $device_provider = null,
            /**
             * Type of the device.
             */
            public string|null $device_type,
            /**
             * Display name of the device, defaults to nickname (if it is set) or `properties.appearance.name`, otherwise. Enables administrators and users to identify the device easily, especially when there are numerous devices.
             */
            public string|null $display_name,
            /**
             * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
             */
            public array $errors,
            /**
             * Indicates whether Seam manages the device. See also [Managed and Unmanaged Devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
             */
            public true|null $is_managed,
            /**
             * Location information for the device.
             */
            public Device\Location|null $location = null,
            /**
             * Optional nickname to describe the device, settable through Seam.
             */
            public string|null $nickname = null,
            /**
             * Properties of the device.
             */
            public Device\Properties|null $properties,
            /**
             * IDs of the spaces the device is in.
             */
            public array|null $space_ids,
            /**
             * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
             */
            public array $warnings,
            /**
             * Unique identifier for the Seam workspace associated with the device.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\Device {
    /**
     * Manufacturer of the device. Represents the hardware brand, which may differ from the provider.
     */
    class DeviceManufacturer
    {
        public static function from_json(mixed $json): DeviceManufacturer|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                display_name: $json->display_name ?? null,
                image_url: $json->image_url ?? null,
                manufacturer: $json->manufacturer ?? null,
            );
        }

        public function __construct(
            /**
             * Display name for the manufacturer, such as `August`, `Yale`, `Salto`, and so on.
             */
            public string|null $display_name,
            /**
             * Image URL for the manufacturer logo.
             */
            public string|null $image_url = null,
            /**
             * Manufacturer identifier, such as `august`, `yale`, `salto`, and so on.
             */
            public string|null $manufacturer,
        ) {}
    }

    /**
     * Provider of the device. Represents the third-party service through which the device is controlled.
     */
    class DeviceProvider
    {
        public static function from_json(mixed $json): DeviceProvider|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_provider_name: $json->device_provider_name ?? null,
                display_name: $json->display_name ?? null,
                image_url: $json->image_url ?? null,
                provider_category: $json->provider_category ?? null,
            );
        }

        public function __construct(
            /**
             * Device provider name. Corresponds to the integration type, such as `august`, `schlage`, `yale_access`, and so on.
             */
            public string|null $device_provider_name,
            /**
             * Display name for the device provider type.
             */
            public string|null $display_name,
            /**
             * Image URL for the device provider.
             */
            public string|null $image_url = null,
            /**
             * Provider category. Indicates the third-party provider type, such as `stable`, for stable integrations, or `internal`, for internal integrations.
             */
            public string|null $provider_category,
        ) {}
    }

    /**
     * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
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
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
                is_device_error: $json->is_device_error ?? null,
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
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error = null,
            public bool|null $is_connected_account_error,
            public bool|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Location information for the device.
     */
    class Location
    {
        public static function from_json(mixed $json): Location|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                location_name: $json->location_name ?? null,
                room_name: $json->room_name ?? null,
                time_zone: $json->time_zone ?? null,
                timezone: $json->timezone ?? null,
            );
        }

        public function __construct(
            /**
             * Name of the device location.
             */
            public string|null $location_name = null,
            /**
             * Name of the room within the device location, when the provider reports one.
             */
            public string|null $room_name = null,
            /**
             * Time zone of the device location.
             */
            public string|null $time_zone = null,
            /**
             * Time zone of the device location.
             *
             * @deprecated Use `time_zone` instead.
             */
            public string|null $timezone = null,
        ) {}
    }

    /**
     * Properties of the device.
     */
    class Properties
    {
        public static function from_json(mixed $json): Properties|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                accessory_keypad: isset($json->accessory_keypad)
                    ? Properties\AccessoryKeypad::from_json(
                        $json->accessory_keypad,
                    )
                    : null,
                active_thermostat_schedule: isset(
                    $json->active_thermostat_schedule,
                )
                    ? Properties\ActiveThermostatSchedule::from_json(
                        $json->active_thermostat_schedule,
                    )
                    : null,
                active_thermostat_schedule_id: $json->active_thermostat_schedule_id ??
                    null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? Properties\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                appearance: isset($json->appearance)
                    ? Properties\Appearance::from_json($json->appearance)
                    : null,
                aqara_metadata: isset($json->aqara_metadata)
                    ? Properties\AqaraMetadata::from_json($json->aqara_metadata)
                    : null,
                assa_abloy_credential_service_metadata: isset(
                    $json->assa_abloy_credential_service_metadata,
                )
                    ? Properties\AssaAbloyCredentialServiceMetadata::from_json(
                        $json->assa_abloy_credential_service_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? Properties\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                august_metadata: isset($json->august_metadata)
                    ? Properties\AugustMetadata::from_json(
                        $json->august_metadata,
                    )
                    : null,
                auto_lock_delay_seconds: $json->auto_lock_delay_seconds ?? null,
                auto_lock_enabled: $json->auto_lock_enabled ?? null,
                available_climate_preset_modes: $json->available_climate_preset_modes ??
                    null,
                available_climate_presets: array_map(
                    fn($a) => Properties\AvailableClimatePresets::from_json($a),
                    $json->available_climate_presets ?? [],
                ),
                available_fan_mode_settings: $json->available_fan_mode_settings ??
                    null,
                available_hvac_mode_settings: $json->available_hvac_mode_settings ??
                    null,
                avigilon_alta_metadata: isset($json->avigilon_alta_metadata)
                    ? Properties\AvigilonAltaMetadata::from_json(
                        $json->avigilon_alta_metadata,
                    )
                    : null,
                backup_access_code_pool_enabled: $json->backup_access_code_pool_enabled ??
                    null,
                battery: isset($json->battery)
                    ? Properties\Battery::from_json($json->battery)
                    : null,
                battery_level: $json->battery_level ?? null,
                brivo_metadata: isset($json->brivo_metadata)
                    ? Properties\BrivoMetadata::from_json($json->brivo_metadata)
                    : null,
                code_constraints: array_map(
                    fn($c) => Properties\CodeConstraints::from_json($c),
                    $json->code_constraints ?? [],
                ),
                controlbyweb_metadata: isset($json->controlbyweb_metadata)
                    ? Properties\ControlbywebMetadata::from_json(
                        $json->controlbyweb_metadata,
                    )
                    : null,
                current_climate_setting: isset($json->current_climate_setting)
                    ? Properties\CurrentClimateSetting::from_json(
                        $json->current_climate_setting,
                    )
                    : null,
                currently_triggering_noise_threshold_ids: $json->currently_triggering_noise_threshold_ids ??
                    null,
                default_climate_setting: isset($json->default_climate_setting)
                    ? Properties\DefaultClimateSetting::from_json(
                        $json->default_climate_setting,
                    )
                    : null,
                door_open: $json->door_open ?? null,
                dormakaba_oracode_metadata: isset(
                    $json->dormakaba_oracode_metadata,
                )
                    ? Properties\DormakabaOracodeMetadata::from_json(
                        $json->dormakaba_oracode_metadata,
                    )
                    : null,
                ecobee_metadata: isset($json->ecobee_metadata)
                    ? Properties\EcobeeMetadata::from_json(
                        $json->ecobee_metadata,
                    )
                    : null,
                fallback_climate_preset_key: $json->fallback_climate_preset_key ??
                    null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                four_suites_metadata: isset($json->four_suites_metadata)
                    ? Properties\FourSuitesMetadata::from_json(
                        $json->four_suites_metadata,
                    )
                    : null,
                genie_metadata: isset($json->genie_metadata)
                    ? Properties\GenieMetadata::from_json($json->genie_metadata)
                    : null,
                has_direct_power: $json->has_direct_power ?? null,
                has_native_entry_events: $json->has_native_entry_events ?? null,
                honeywell_resideo_metadata: isset(
                    $json->honeywell_resideo_metadata,
                )
                    ? Properties\HoneywellResideoMetadata::from_json(
                        $json->honeywell_resideo_metadata,
                    )
                    : null,
                igloo_metadata: isset($json->igloo_metadata)
                    ? Properties\IglooMetadata::from_json($json->igloo_metadata)
                    : null,
                igloohome_metadata: isset($json->igloohome_metadata)
                    ? Properties\IgloohomeMetadata::from_json(
                        $json->igloohome_metadata,
                    )
                    : null,
                image_alt_text: $json->image_alt_text ?? null,
                image_url: $json->image_url ?? null,
                is_cooling: $json->is_cooling ?? null,
                is_fan_running: $json->is_fan_running ?? null,
                is_heating: $json->is_heating ?? null,
                is_temporary_manual_override_active: $json->is_temporary_manual_override_active ??
                    null,
                keynest_metadata: isset($json->keynest_metadata)
                    ? Properties\KeynestMetadata::from_json(
                        $json->keynest_metadata,
                    )
                    : null,
                keypad_battery: isset($json->keypad_battery)
                    ? Properties\KeypadBattery::from_json($json->keypad_battery)
                    : null,
                kisi_metadata: isset($json->kisi_metadata)
                    ? Properties\KisiMetadata::from_json($json->kisi_metadata)
                    : null,
                korelock_metadata: isset($json->korelock_metadata)
                    ? Properties\KorelockMetadata::from_json(
                        $json->korelock_metadata,
                    )
                    : null,
                kwikset_metadata: isset($json->kwikset_metadata)
                    ? Properties\KwiksetMetadata::from_json(
                        $json->kwikset_metadata,
                    )
                    : null,
                locked: $json->locked ?? null,
                lockly_metadata: isset($json->lockly_metadata)
                    ? Properties\LocklyMetadata::from_json(
                        $json->lockly_metadata,
                    )
                    : null,
                manufacturer: $json->manufacturer ?? null,
                max_active_codes_supported: $json->max_active_codes_supported ??
                    null,
                max_cooling_set_point_celsius: $json->max_cooling_set_point_celsius ??
                    null,
                max_cooling_set_point_fahrenheit: $json->max_cooling_set_point_fahrenheit ??
                    null,
                max_heating_set_point_celsius: $json->max_heating_set_point_celsius ??
                    null,
                max_heating_set_point_fahrenheit: $json->max_heating_set_point_fahrenheit ??
                    null,
                max_thermostat_daily_program_periods_per_day: $json->max_thermostat_daily_program_periods_per_day ??
                    null,
                max_unique_climate_presets_per_thermostat_weekly_program: $json->max_unique_climate_presets_per_thermostat_weekly_program ??
                    null,
                min_cooling_set_point_celsius: $json->min_cooling_set_point_celsius ??
                    null,
                min_cooling_set_point_fahrenheit: $json->min_cooling_set_point_fahrenheit ??
                    null,
                min_heating_cooling_delta_celsius: $json->min_heating_cooling_delta_celsius ??
                    null,
                min_heating_cooling_delta_fahrenheit: $json->min_heating_cooling_delta_fahrenheit ??
                    null,
                min_heating_set_point_celsius: $json->min_heating_set_point_celsius ??
                    null,
                min_heating_set_point_fahrenheit: $json->min_heating_set_point_fahrenheit ??
                    null,
                minut_metadata: isset($json->minut_metadata)
                    ? Properties\MinutMetadata::from_json($json->minut_metadata)
                    : null,
                model: isset($json->model)
                    ? Properties\Model::from_json($json->model)
                    : null,
                name: $json->name ?? null,
                nest_metadata: isset($json->nest_metadata)
                    ? Properties\NestMetadata::from_json($json->nest_metadata)
                    : null,
                noise_level_decibels: $json->noise_level_decibels ?? null,
                noiseaware_metadata: isset($json->noiseaware_metadata)
                    ? Properties\NoiseawareMetadata::from_json(
                        $json->noiseaware_metadata,
                    )
                    : null,
                nuki_metadata: isset($json->nuki_metadata)
                    ? Properties\NukiMetadata::from_json($json->nuki_metadata)
                    : null,
                offline_access_codes_enabled: $json->offline_access_codes_enabled ??
                    null,
                offline_time_frame_options: array_map(
                    fn($o) => Properties\OfflineTimeFrameOptions::from_json($o),
                    $json->offline_time_frame_options ?? [],
                ),
                omnitec_metadata: isset($json->omnitec_metadata)
                    ? Properties\OmnitecMetadata::from_json(
                        $json->omnitec_metadata,
                    )
                    : null,
                online: $json->online ?? null,
                online_access_codes_enabled: $json->online_access_codes_enabled ??
                    null,
                online_time_frame_options: array_map(
                    fn($o) => Properties\OnlineTimeFrameOptions::from_json($o),
                    $json->online_time_frame_options ?? [],
                ),
                relative_humidity: $json->relative_humidity ?? null,
                ring_metadata: isset($json->ring_metadata)
                    ? Properties\RingMetadata::from_json($json->ring_metadata)
                    : null,
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? Properties\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
                salto_metadata: isset($json->salto_metadata)
                    ? Properties\SaltoMetadata::from_json($json->salto_metadata)
                    : null,
                salto_space_credential_service_metadata: isset(
                    $json->salto_space_credential_service_metadata,
                )
                    ? Properties\SaltoSpaceCredentialServiceMetadata::from_json(
                        $json->salto_space_credential_service_metadata,
                    )
                    : null,
                schlage_metadata: isset($json->schlage_metadata)
                    ? Properties\SchlageMetadata::from_json(
                        $json->schlage_metadata,
                    )
                    : null,
                seam_bridge_metadata: isset($json->seam_bridge_metadata)
                    ? Properties\SeamBridgeMetadata::from_json(
                        $json->seam_bridge_metadata,
                    )
                    : null,
                sensi_metadata: isset($json->sensi_metadata)
                    ? Properties\SensiMetadata::from_json($json->sensi_metadata)
                    : null,
                serial_number: $json->serial_number ?? null,
                smartthings_metadata: isset($json->smartthings_metadata)
                    ? Properties\SmartthingsMetadata::from_json(
                        $json->smartthings_metadata,
                    )
                    : null,
                supported_code_lengths: $json->supported_code_lengths ?? null,
                supports_accessory_keypad: $json->supports_accessory_keypad ??
                    null,
                supports_backup_access_code_pool: $json->supports_backup_access_code_pool ??
                    null,
                supports_offline_access_codes: $json->supports_offline_access_codes ??
                    null,
                tado_metadata: isset($json->tado_metadata)
                    ? Properties\TadoMetadata::from_json($json->tado_metadata)
                    : null,
                tedee_metadata: isset($json->tedee_metadata)
                    ? Properties\TedeeMetadata::from_json($json->tedee_metadata)
                    : null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                temperature_threshold: isset($json->temperature_threshold)
                    ? Properties\TemperatureThreshold::from_json(
                        $json->temperature_threshold,
                    )
                    : null,
                thermostat_daily_program_period_precision_minutes: $json->thermostat_daily_program_period_precision_minutes ??
                    null,
                thermostat_daily_programs: array_map(
                    fn($t) => Properties\ThermostatDailyPrograms::from_json($t),
                    $json->thermostat_daily_programs ?? [],
                ),
                thermostat_weekly_program: isset(
                    $json->thermostat_weekly_program,
                )
                    ? Properties\ThermostatWeeklyProgram::from_json(
                        $json->thermostat_weekly_program,
                    )
                    : null,
                ttlock_metadata: isset($json->ttlock_metadata)
                    ? Properties\TtlockMetadata::from_json(
                        $json->ttlock_metadata,
                    )
                    : null,
                two_n_metadata: isset($json->two_n_metadata)
                    ? Properties\TwoNMetadata::from_json($json->two_n_metadata)
                    : null,
                ultraloq_metadata: isset($json->ultraloq_metadata)
                    ? Properties\UltraloqMetadata::from_json(
                        $json->ultraloq_metadata,
                    )
                    : null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? Properties\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
                wyze_metadata: isset($json->wyze_metadata)
                    ? Properties\WyzeMetadata::from_json($json->wyze_metadata)
                    : null,
                yacan_metadata: isset($json->yacan_metadata)
                    ? Properties\YacanMetadata::from_json($json->yacan_metadata)
                    : null,
            );
        }

        public function __construct(
            /**
             * Accessory keypad properties and state.
             */
            public Properties\AccessoryKeypad|null $accessory_keypad = null,
            /**
             * Active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             *
             * @deprecated Use `active_thermostat_schedule_id` with `/thermostats/schedules/get` instead.
             */
            public Properties\ActiveThermostatSchedule|null $active_thermostat_schedule = null,
            /**
             * ID of the active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $active_thermostat_schedule_id = null,
            /**
             * Metadata for an Akiles device.
             */
            public Properties\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Appearance-related properties, as reported by the device.
             */
            public Properties\Appearance|null $appearance,
            /**
             * Metadata for an Aqara device.
             */
            public Properties\AqaraMetadata|null $aqara_metadata = null,
            /**
             * ASSA ABLOY Credential Service metadata for the phone.
             */
            public Properties\AssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata = null,
            /**
             * Metadata for an ASSA ABLOY Vostio system.
             */
            public Properties\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Metadata for an August device.
             */
            public Properties\AugustMetadata|null $august_metadata = null,
            /**
             * The delay in seconds before the lock automatically locks after being unlocked.
             */
            public float|null $auto_lock_delay_seconds = null,
            /**
             * Indicates whether automatic locking is enabled.
             */
            public bool|null $auto_lock_enabled = null,
            /**
             * Climate preset modes that the thermostat supports, such as "home", "away", "wake", "sleep", "occupied", and "unoccupied".
             */
            public array|null $available_climate_preset_modes = null,
            /**
             * Available [climate presets](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for the thermostat.
             */
            public array|null $available_climate_presets = null,
            /**
             * Fan mode settings that the thermostat supports.
             */
            public array|null $available_fan_mode_settings = null,
            /**
             * HVAC mode settings that the thermostat supports.
             */
            public array|null $available_hvac_mode_settings = null,
            /**
             * Metadata for an Avigilon Alta system.
             */
            public Properties\AvigilonAltaMetadata|null $avigilon_alta_metadata = null,
            /**
             * Indicates whether the [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is currently enabled for the device. To disable it, set this to `false` using [/devices/update](https://docs.seam.co/api/devices/update).
             */
            public bool|null $backup_access_code_pool_enabled = null,
            /**
             * Represents the current status of the battery charge level.
             */
            public Properties\Battery|null $battery = null,
            /**
             * Indicates the battery level of the device as a decimal value between 0 and 1, inclusive.
             */
            public float|null $battery_level = null,
            /**
             * Metadata for a Brivo device.
             */
            public Properties\BrivoMetadata|null $brivo_metadata = null,
            /**
             * Constraints on access codes for the device. Seam represents each constraint as an object with a `constraint_type` property. Depending on the constraint type, there may also be additional properties. Note that some constraints are manufacturer- or device-specific.
             */
            public array|null $code_constraints = null,
            /**
             * Metadata for a ControlByWeb device.
             */
            public Properties\ControlbywebMetadata|null $controlbyweb_metadata = null,
            /**
             * Current climate setting.
             */
            public Properties\CurrentClimateSetting|null $current_climate_setting = null,
            /**
             * Array of noise threshold IDs that are currently triggering.
             */
            public array|null $currently_triggering_noise_threshold_ids = null,
            /**
             * @deprecated use fallback_climate_preset_key to specify a fallback climate preset instead.
             */
            public Properties\DefaultClimateSetting|null $default_climate_setting = null,
            /**
             * Indicates whether the door is open.
             */
            public bool|null $door_open = null,
            /**
             * Metadata for a dormakaba Oracode device.
             */
            public Properties\DormakabaOracodeMetadata|null $dormakaba_oracode_metadata = null,
            /**
             * Metadata for an ecobee device.
             */
            public Properties\EcobeeMetadata|null $ecobee_metadata = null,
            /**
             * Key of the [fallback climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets/setting-the-fallback-climate-preset) for the thermostat.
             */
            public string|null $fallback_climate_preset_key = null,
            /**
             * @deprecated Use `current_climate_setting.fan_mode_setting` instead.
             */
            public string|null $fan_mode_setting = null,
            /**
             * Metadata for a 4SUITES device.
             */
            public Properties\FourSuitesMetadata|null $four_suites_metadata = null,
            /**
             * Metadata for a Genie device.
             */
            public Properties\GenieMetadata|null $genie_metadata = null,
            /**
             * Indicates whether the device has direct power.
             */
            public bool|null $has_direct_power = null,
            /**
             * Indicates whether the device supports native entry events.
             */
            public bool|null $has_native_entry_events = null,
            /**
             * Metadata for a Honeywell Resideo device.
             */
            public Properties\HoneywellResideoMetadata|null $honeywell_resideo_metadata = null,
            /**
             * Metadata for an igloo device.
             */
            public Properties\IglooMetadata|null $igloo_metadata = null,
            /**
             * Metadata for an igloohome device.
             */
            public Properties\IgloohomeMetadata|null $igloohome_metadata = null,
            /**
             * Alt text for the device image.
             */
            public string|null $image_alt_text = null,
            /**
             * Image URL for the device.
             */
            public string|null $image_url = null,
            /**
             * Indicates whether the connected HVAC system is currently cooling, as reported by the thermostat.
             */
            public bool|null $is_cooling = null,
            /**
             * Indicates whether the fan in the connected HVAC system is currently running, as reported by the thermostat.
             */
            public bool|null $is_fan_running = null,
            /**
             * Indicates whether the connected HVAC system is currently heating, as reported by the thermostat.
             */
            public bool|null $is_heating = null,
            /**
             * Indicates whether the current thermostat settings differ from the most recent active program or schedule that Seam activated. For this condition to occur, `current_climate_setting.manual_override_allowed` must also be `true`.
             */
            public bool|null $is_temporary_manual_override_active = null,
            /**
             * Metadata for a KeyNest device.
             */
            public Properties\KeynestMetadata|null $keynest_metadata = null,
            /**
             * Keypad battery status.
             */
            public Properties\KeypadBattery|null $keypad_battery = null,
            /**
             * Metadata for a Kisi device.
             */
            public Properties\KisiMetadata|null $kisi_metadata = null,
            /**
             * Metadata for a Korelock device.
             */
            public Properties\KorelockMetadata|null $korelock_metadata = null,
            /**
             * Metadata for a Kwikset device.
             */
            public Properties\KwiksetMetadata|null $kwikset_metadata = null,
            /**
             * Indicates whether the lock is locked.
             */
            public bool|null $locked = null,
            /**
             * Metadata for a Lockly device.
             */
            public Properties\LocklyMetadata|null $lockly_metadata = null,
            /**
             * Manufacturer of the device. When a device, such as a smart lock, is connected through a smart hub, the manufacturer of the device might be different from that of the smart hub.
             */
            public string|null $manufacturer = null,
            /**
             * Maximum number of active access codes that the device supports.
             */
            public float|null $max_active_codes_supported = null,
            /**
             * Maximum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °C.
             */
            public float|null $max_cooling_set_point_celsius = null,
            /**
             * Maximum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °F.
             */
            public float|null $max_cooling_set_point_fahrenheit = null,
            /**
             * Maximum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °C.
             */
            public float|null $max_heating_set_point_celsius = null,
            /**
             * Maximum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °F.
             */
            public float|null $max_heating_set_point_fahrenheit = null,
            /**
             * Maximum number of periods that the thermostat can support per day. For example, if the thermostat supports 4 periods per day, this value is 4.
             */
            public float|null $max_thermostat_daily_program_periods_per_day = null,
            /**
             * Maximum number of climate presets that the thermostat can support for weekly programming.
             */
            public float|null $max_unique_climate_presets_per_thermostat_weekly_program = null,
            /**
             * Minimum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °C.
             */
            public float|null $min_cooling_set_point_celsius = null,
            /**
             * Minimum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °F.
             */
            public float|null $min_cooling_set_point_fahrenheit = null,
            /**
             * Minimum [temperature difference](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#minimum-heating-cooling-temperature-delta) in °C between the cooling and heating set points when in heat-cool (auto) mode.
             */
            public float|null $min_heating_cooling_delta_celsius = null,
            /**
             * Minimum [temperature difference](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#minimum-heating-cooling-temperature-delta) in °F between the cooling and heating set points when in heat-cool (auto) mode.
             */
            public float|null $min_heating_cooling_delta_fahrenheit = null,
            /**
             * Minimum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °C.
             */
            public float|null $min_heating_set_point_celsius = null,
            /**
             * Minimum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °F.
             */
            public float|null $min_heating_set_point_fahrenheit = null,
            /**
             * Metadata for a Minut device.
             */
            public Properties\MinutMetadata|null $minut_metadata = null,
            /**
             * Device model-related properties.
             */
            public Properties\Model|null $model,
            /**
             * Name of the device.
             *
             * @deprecated use device.display_name instead
             */
            public string|null $name,
            /**
             * Metadata for a Google Nest device.
             */
            public Properties\NestMetadata|null $nest_metadata = null,
            /**
             * Indicates current noise level in decibels, if the device supports noise detection.
             */
            public float|null $noise_level_decibels = null,
            /**
             * Metadata for a NoiseAware device.
             */
            public Properties\NoiseawareMetadata|null $noiseaware_metadata = null,
            /**
             * Metadata for a Nuki device.
             */
            public Properties\NukiMetadata|null $nuki_metadata = null,
            /**
             * Indicates whether it is currently possible to use offline access codes for the device.
             *
             * @deprecated use device.can_program_offline_access_codes
             */
            public bool|null $offline_access_codes_enabled = null,
            /**
             * Time frames that may be requested when creating an offline access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
             */
            public array|null $offline_time_frame_options = null,
            /**
             * Metadata for an Omnitec device.
             */
            public Properties\OmnitecMetadata|null $omnitec_metadata = null,
            /**
             * Indicates whether the device is online.
             */
            public bool|null $online,
            /**
             * Indicates whether it is currently possible to use online access codes for the device.
             *
             * @deprecated use device.can_program_online_access_codes
             */
            public bool|null $online_access_codes_enabled = null,
            /**
             * Time frames that may be requested when creating an online access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
             */
            public array|null $online_time_frame_options = null,
            /**
             * Reported relative humidity, as a value between 0 and 1, inclusive.
             */
            public float|null $relative_humidity = null,
            /**
             * Metadata for a Ring device.
             */
            public Properties\RingMetadata|null $ring_metadata = null,
            /**
             * Metadata for a Salto KS device.
             */
            public Properties\SaltoKsMetadata|null $salto_ks_metadata = null,
            /**
             * Metada for a Salto device.
             *
             * @deprecated Use `salto_ks_metadata` instead.
             */
            public Properties\SaltoMetadata|null $salto_metadata = null,
            /**
             * Salto Space credential service metadata for the phone.
             */
            public Properties\SaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata = null,
            /**
             * Metadata for a Schlage device.
             */
            public Properties\SchlageMetadata|null $schlage_metadata = null,
            /**
             * Metadata for Seam Bridge.
             */
            public Properties\SeamBridgeMetadata|null $seam_bridge_metadata = null,
            /**
             * Metadata for a Sensi device.
             */
            public Properties\SensiMetadata|null $sensi_metadata = null,
            /**
             * Serial number of the device.
             */
            public string|null $serial_number = null,
            /**
             * Metadata for a SmartThings device.
             */
            public Properties\SmartthingsMetadata|null $smartthings_metadata = null,
            /**
             * Supported code lengths for access codes.
             */
            public array|null $supported_code_lengths = null,
            /**
             * @deprecated use device.properties.model.can_connect_accessory_keypad
             */
            public bool|null $supports_accessory_keypad = null,
            /**
             * Indicates whether the device supports a [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes).
             */
            public bool|null $supports_backup_access_code_pool = null,
            /**
             * @deprecated use offline_access_codes_enabled
             */
            public bool|null $supports_offline_access_codes = null,
            /**
             * Metadata for a tado° device.
             */
            public Properties\TadoMetadata|null $tado_metadata = null,
            /**
             * Metadata for a Tedee device.
             */
            public Properties\TedeeMetadata|null $tedee_metadata = null,
            /**
             * Reported temperature in °C.
             */
            public float|null $temperature_celsius = null,
            /**
             * Reported temperature in °F.
             */
            public float|null $temperature_fahrenheit = null,
            /**
             * Current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
             */
            public Properties\TemperatureThreshold|null $temperature_threshold = null,
            /**
             * Precision of the thermostat's period in minutes. For example, if the thermostat supports 15-minute periods, this value is 15. All values are relative to the top of the hour, so for 15 minutes, the periods would be 0, 15, 30, and 45 minutes past the hour.
             */
            public float|null $thermostat_daily_program_period_precision_minutes = null,
            /**
             * Configured [daily programs](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
             */
            public array|null $thermostat_daily_programs = null,
            /**
             * Current [weekly program](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
             */
            public Properties\ThermostatWeeklyProgram|null $thermostat_weekly_program = null,
            /**
             * Metadata for a TTLock device.
             */
            public Properties\TtlockMetadata|null $ttlock_metadata = null,
            /**
             * Metadata for a 2N device.
             */
            public Properties\TwoNMetadata|null $two_n_metadata = null,
            /**
             * Metadata for an Ultraloq device.
             */
            public Properties\UltraloqMetadata|null $ultraloq_metadata = null,
            /**
             * Metadata for an ASSA ABLOY Visionline system.
             */
            public Properties\VisionlineMetadata|null $visionline_metadata = null,
            /**
             * Metadata for a Wyze device.
             */
            public Properties\WyzeMetadata|null $wyze_metadata = null,
            /**
             * Metadata for a Yacan device.
             */
            public Properties\YacanMetadata|null $yacan_metadata = null,
        ) {}
    }

    /**
     * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                active_access_code_count: $json->active_access_code_count ??
                    null,
                created_at: $json->created_at ?? null,
                max_active_access_code_count: $json->max_active_access_code_count ??
                    null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Number of active access codes on the device when the warning was set.
             */
            public int|null $active_access_code_count,
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Maximum number of active access codes supported by the device.
             */
            public int|null $max_active_access_code_count,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties {
    /**
     * Accessory keypad properties and state.
     */
    class AccessoryKeypad
    {
        public static function from_json(mixed $json): AccessoryKeypad|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                battery: isset($json->battery)
                    ? AccessoryKeypad\Battery::from_json($json->battery)
                    : null,
                is_connected: $json->is_connected ?? null,
            );
        }

        public function __construct(
            /**
             * Keypad battery properties.
             */
            public AccessoryKeypad\Battery|null $battery = null,
            /**
             * Indicates if an accessory keypad is connected to the device.
             */
            public bool|null $is_connected,
        ) {}
    }

    /**
     * Appearance-related properties, as reported by the device.
     */
    class Appearance
    {
        public static function from_json(mixed $json): Appearance|null
        {
            if (!$json) {
                return null;
            }
            return new self(name: $json->name ?? null);
        }

        public function __construct(
            /**
             * Name of the device as seen from the provider API and application, not settable through Seam.
             */
            public string|null $name,
        ) {}
    }

    /**
     * Represents the current status of the battery charge level.
     */
    class Battery
    {
        public static function from_json(mixed $json): Battery|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                level: $json->level ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * Battery charge level as a value between 0 and 1, inclusive.
             */
            public float|null $level,
            /**
             * Represents the current status of the battery charge level. Values are `critical`, which indicates an extremely low level, suggesting imminent shutdown or an urgent need for charging; `low`, which signifies that the battery is under the preferred threshold and should be charged soon; `good`, which denotes a satisfactory charge level, adequate for normal use without the immediate need for recharging; and `full`, which represents a battery that is fully charged, providing the maximum duration of usage.
             */
            public string|null $status,
        ) {}
    }

    /**
     * Device model-related properties.
     */
    class Model
    {
        public static function from_json(mixed $json): Model|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                accessory_keypad_supported: $json->accessory_keypad_supported ??
                    null,
                can_connect_accessory_keypad: $json->can_connect_accessory_keypad ??
                    null,
                display_name: $json->display_name ?? null,
                has_built_in_keypad: $json->has_built_in_keypad ?? null,
                manufacturer_display_name: $json->manufacturer_display_name ??
                    null,
                offline_access_codes_supported: $json->offline_access_codes_supported ??
                    null,
                online_access_codes_supported: $json->online_access_codes_supported ??
                    null,
            );
        }

        public function __construct(
            /**
             * @deprecated use device.properties.model.can_connect_accessory_keypad
             */
            public bool|null $accessory_keypad_supported = null,
            /**
             * Indicates whether the device can connect a accessory keypad.
             */
            public bool|null $can_connect_accessory_keypad = null,
            /**
             * Display name of the device model.
             */
            public string|null $display_name,
            /**
             * Indicates whether the device has a built in accessory keypad.
             */
            public bool|null $has_built_in_keypad = null,
            /**
             * Display name that corresponds to the manufacturer-specific terminology for the device.
             */
            public string|null $manufacturer_display_name,
            /**
             * @deprecated use device.can_program_offline_access_codes.
             */
            public bool|null $offline_access_codes_supported = null,
            /**
             * @deprecated use device.can_program_online_access_codes.
             */
            public bool|null $online_access_codes_supported = null,
        ) {}
    }

    /**
     * ASSA ABLOY Credential Service metadata for the phone.
     */
    class AssaAbloyCredentialServiceMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyCredentialServiceMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                endpoints: array_map(
                    fn(
                        $e,
                    ) => AssaAbloyCredentialServiceMetadata\Endpoints::from_json(
                        $e,
                    ),
                    $json->endpoints ?? [],
                ),
                has_active_endpoint: $json->has_active_endpoint ?? null,
            );
        }

        public function __construct(
            /**
             * Endpoints associated with the phone.
             */
            public array|null $endpoints = null,
            /**
             * Indicates whether the credential service has active endpoints associated with the phone.
             */
            public bool|null $has_active_endpoint = null,
        ) {}
    }

    /**
     * Salto Space credential service metadata for the phone.
     */
    class SaltoSpaceCredentialServiceMetadata
    {
        public static function from_json(
            mixed $json,
        ): SaltoSpaceCredentialServiceMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(has_active_phone: $json->has_active_phone ?? null);
        }

        public function __construct(
            /**
             * Indicates whether the credential service has an active associated phone.
             */
            public bool|null $has_active_phone = null,
        ) {}
    }

    /**
     * Metadata for an Akiles device.
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                _member_group_id: $json->_member_group_id ?? null,
                gadget_id: $json->gadget_id ?? null,
                gadget_name: $json->gadget_name ?? null,
                product_name: $json->product_name ?? null,
            );
        }

        public function __construct(
            /**
             * Group ID to which to add users for an Akiles device.
             */
            public string|null $_member_group_id = null,
            /**
             * Gadget ID for an Akiles device.
             */
            public string|null $gadget_id = null,
            /**
             * Gadget name for an Akiles device.
             */
            public string|null $gadget_name = null,
            /**
             * Product name for an Akiles device.
             */
            public string|null $product_name = null,
        ) {}
    }

    /**
     * Metadata for an Aqara device.
     */
    class AqaraMetadata
    {
        public static function from_json(mixed $json): AqaraMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_name: $json->device_name ?? null,
                did: $json->did ?? null,
                firmware_version: $json->firmware_version ?? null,
                model: $json->model ?? null,
                model_type: $json->model_type ?? null,
                parent_did: $json->parent_did ?? null,
                position_id: $json->position_id ?? null,
                time_zone: $json->time_zone ?? null,
            );
        }

        public function __construct(
            /**
             * Device name for an Aqara device.
             */
            public string|null $device_name = null,
            /**
             * Device ID (did) for an Aqara device.
             */
            public string|null $did = null,
            /**
             * Firmware version for an Aqara device.
             */
            public string|null $firmware_version = null,
            /**
             * Model identifier for an Aqara device.
             */
            public string|null $model = null,
            /**
             * Model type for an Aqara device.
             */
            public float|null $model_type = null,
            /**
             * Parent gateway device ID for an Aqara device.
             */
            public string|null $parent_did = null,
            /**
             * Position (room) ID for an Aqara device.
             */
            public string|null $position_id = null,
            /**
             * Time zone reported for an Aqara device (e.g. GMT-07:00).
             */
            public string|null $time_zone = null,
        ) {}
    }

    /**
     * Metadata for an ASSA ABLOY Vostio system.
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(encoder_name: $json->encoder_name ?? null);
        }

        public function __construct(
            /**
             * Encoder name for an ASSA ABLOY Vostio system.
             */
            public string|null $encoder_name = null,
        ) {}
    }

    /**
     * Metadata for an August device.
     */
    class AugustMetadata
    {
        public static function from_json(mixed $json): AugustMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                has_keypad: $json->has_keypad ?? null,
                house_id: $json->house_id ?? null,
                house_name: $json->house_name ?? null,
                keypad_battery_level: $json->keypad_battery_level ?? null,
                lock_id: $json->lock_id ?? null,
                lock_name: $json->lock_name ?? null,
                model: $json->model ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether an August device has a keypad.
             */
            public bool|null $has_keypad = null,
            /**
             * House ID for an August device.
             */
            public string|null $house_id = null,
            /**
             * House name for an August device.
             */
            public string|null $house_name = null,
            /**
             * Keypad battery level for an August device.
             */
            public string|null $keypad_battery_level = null,
            /**
             * Lock ID for an August device.
             */
            public string|null $lock_id = null,
            /**
             * Lock name for an August device.
             */
            public string|null $lock_name = null,
            /**
             * Model for an August device.
             */
            public string|null $model = null,
        ) {}
    }

    /**
     * Metadata for an Avigilon Alta system.
     */
    class AvigilonAltaMetadata
    {
        public static function from_json(mixed $json): AvigilonAltaMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                entry_name: $json->entry_name ?? null,
                entry_relays_total_count: $json->entry_relays_total_count ??
                    null,
                org_name: $json->org_name ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
                zone_id: $json->zone_id ?? null,
                zone_name: $json->zone_name ?? null,
            );
        }

        public function __construct(
            /**
             * Entry name for an Avigilon Alta system.
             */
            public string|null $entry_name = null,
            /**
             * Total count of entry relays for an Avigilon Alta system.
             */
            public float|null $entry_relays_total_count = null,
            /**
             * Organization name for an Avigilon Alta system.
             */
            public string|null $org_name = null,
            /**
             * Site ID for an Avigilon Alta system.
             */
            public float|null $site_id = null,
            /**
             * Site name for an Avigilon Alta system.
             */
            public string|null $site_name = null,
            /**
             * Zone ID for an Avigilon Alta system.
             */
            public float|null $zone_id = null,
            /**
             * Zone name for an Avigilon Alta system.
             */
            public string|null $zone_name = null,
        ) {}
    }

    /**
     * Metadata for a Brivo device.
     */
    class BrivoMetadata
    {
        public static function from_json(mixed $json): BrivoMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                activation_enabled: $json->activation_enabled ?? null,
                device_name: $json->device_name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the Brivo access point has activation (remote unlock) enabled.
             */
            public bool|null $activation_enabled = null,
            /**
             * Device name for a Brivo device.
             */
            public string|null $device_name = null,
        ) {}
    }

    /**
     * Metadata for a ControlByWeb device.
     */
    class ControlbywebMetadata
    {
        public static function from_json(mixed $json): ControlbywebMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                relay_name: $json->relay_name ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a ControlByWeb device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a ControlByWeb device.
             */
            public string|null $device_name = null,
            /**
             * Relay name for a ControlByWeb device.
             */
            public string|null $relay_name = null,
        ) {}
    }

    /**
     * Metadata for a dormakaba Oracode device.
     */
    class DormakabaOracodeMetadata
    {
        public static function from_json(
            mixed $json,
        ): DormakabaOracodeMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                door_id: $json->door_id ?? null,
                door_is_wireless: $json->door_is_wireless ?? null,
                door_name: $json->door_name ?? null,
                iana_timezone: $json->iana_timezone ?? null,
                predefined_time_slots: array_map(
                    fn(
                        $p,
                    ) => DormakabaOracodeMetadata\PredefinedTimeSlots::from_json(
                        $p,
                    ),
                    $json->predefined_time_slots ?? [],
                ),
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a dormakaba Oracode device.
             */
            public string|null $device_id = null,
            /**
             * Door ID for a dormakaba Oracode device.
             */
            public float|null $door_id = null,
            /**
             * Indicates whether a door is wireless for a dormakaba Oracode device.
             */
            public bool|null $door_is_wireless = null,
            /**
             * Door name for a dormakaba Oracode device.
             */
            public string|null $door_name = null,
            /**
             * IANA time zone for a dormakaba Oracode device.
             */
            public string|null $iana_timezone = null,
            /**
             * Predefined time slots for a dormakaba Oracode device.
             */
            public array|null $predefined_time_slots = null,
            /**
             * Site ID for a dormakaba Oracode device.
             *
             * @deprecated Previously marked as "@DEPRECATED."
             */
            public float|null $site_id = null,
            /**
             * Site name for a dormakaba Oracode device.
             */
            public string|null $site_name = null,
        ) {}
    }

    /**
     * Metadata for an ecobee device.
     */
    class EcobeeMetadata
    {
        public static function from_json(mixed $json): EcobeeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_name: $json->device_name ?? null,
                ecobee_device_id: $json->ecobee_device_id ?? null,
            );
        }

        public function __construct(
            /**
             * Device name for an ecobee device.
             */
            public string|null $device_name = null,
            /**
             * Device ID for an ecobee device.
             */
            public string|null $ecobee_device_id = null,
        ) {}
    }

    /**
     * Metadata for a 4SUITES device.
     */
    class FourSuitesMetadata
    {
        public static function from_json(mixed $json): FourSuitesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                reclose_delay_in_seconds: $json->reclose_delay_in_seconds ??
                    null,
            );
        }

        public function __construct(
            /**
             * Device ID for a 4SUITES device.
             */
            public float|null $device_id = null,
            /**
             * Device name for a 4SUITES device.
             */
            public string|null $device_name = null,
            /**
             * Reclose delay, in seconds, for a 4SUITES device.
             */
            public float|null $reclose_delay_in_seconds = null,
        ) {}
    }

    /**
     * Metadata for a Genie device.
     */
    class GenieMetadata
    {
        public static function from_json(mixed $json): GenieMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_name: $json->device_name ?? null,
                door_name: $json->door_name ?? null,
            );
        }

        public function __construct(
            /**
             * Lock name for a Genie device.
             */
            public string|null $device_name = null,
            /**
             * Door name for a Genie device.
             */
            public string|null $door_name = null,
        ) {}
    }

    /**
     * Metadata for a Honeywell Resideo device.
     */
    class HoneywellResideoMetadata
    {
        public static function from_json(
            mixed $json,
        ): HoneywellResideoMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                device_name: $json->device_name ?? null,
                honeywell_resideo_device_id: $json->honeywell_resideo_device_id ??
                    null,
            );
        }

        public function __construct(
            /**
             * Device name for a Honeywell Resideo device.
             */
            public string|null $device_name = null,
            /**
             * Device ID for a Honeywell Resideo device.
             */
            public string|null $honeywell_resideo_device_id = null,
        ) {}
    }

    /**
     * Metadata for an igloo device.
     */
    class IglooMetadata
    {
        public static function from_json(mixed $json): IglooMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                bridge_id: $json->bridge_id ?? null,
                device_id: $json->device_id ?? null,
                model: $json->model ?? null,
            );
        }

        public function __construct(
            /**
             * Bridge ID for an igloo device.
             */
            public string|null $bridge_id = null,
            /**
             * Device ID for an igloo device.
             */
            public string|null $device_id = null,
            /**
             * Model for an igloo device.
             */
            public string|null $model = null,
        ) {}
    }

    /**
     * Metadata for an igloohome device.
     */
    class IgloohomeMetadata
    {
        public static function from_json(mixed $json): IgloohomeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                bridge_id: $json->bridge_id ?? null,
                bridge_name: $json->bridge_name ?? null,
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                is_accessory_keypad_linked_to_bridge: $json->is_accessory_keypad_linked_to_bridge ??
                    null,
                keypad_id: $json->keypad_id ?? null,
            );
        }

        public function __construct(
            /**
             * Bridge ID for an igloohome device.
             */
            public string|null $bridge_id = null,
            /**
             * Bridge name for an igloohome device.
             */
            public string|null $bridge_name = null,
            /**
             * Device ID for an igloohome device.
             */
            public string|null $device_id = null,
            /**
             * Device name for an igloohome device.
             */
            public string|null $device_name = null,
            /**
             * Indicates whether a keypad is linked to a bridge for an igloohome device.
             */
            public bool|null $is_accessory_keypad_linked_to_bridge = null,
            /**
             * Keypad ID for an igloohome device.
             */
            public string|null $keypad_id = null,
        ) {}
    }

    /**
     * Metadata for a KeyNest device.
     */
    class KeynestMetadata
    {
        public static function from_json(mixed $json): KeynestMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                address: $json->address ?? null,
                current_or_last_store_id: $json->current_or_last_store_id ??
                    null,
                current_status: $json->current_status ?? null,
                current_user_company: $json->current_user_company ?? null,
                current_user_email: $json->current_user_email ?? null,
                current_user_name: $json->current_user_name ?? null,
                current_user_phone_number: $json->current_user_phone_number ??
                    null,
                default_office_id: $json->default_office_id ?? null,
                device_name: $json->device_name ?? null,
                fob_id: $json->fob_id ?? null,
                handover_method: $json->handover_method ?? null,
                has_photo: $json->has_photo ?? null,
                is_quadient_locker: $json->is_quadient_locker ?? null,
                key_id: $json->key_id ?? null,
                key_notes: $json->key_notes ?? null,
                keynest_app_user: $json->keynest_app_user ?? null,
                last_movement: $json->last_movement ?? null,
                property_id: $json->property_id ?? null,
                property_postcode: $json->property_postcode ?? null,
                status_type: $json->status_type ?? null,
                subscription_plan: $json->subscription_plan ?? null,
            );
        }

        public function __construct(
            /**
             * Address for a KeyNest device.
             */
            public string|null $address = null,
            /**
             * Current or last store ID for a KeyNest device.
             */
            public float|null $current_or_last_store_id = null,
            /**
             * Current status for a KeyNest device.
             */
            public string|null $current_status = null,
            /**
             * Current user company for a KeyNest device.
             */
            public string|null $current_user_company = null,
            /**
             * Current user email for a KeyNest device.
             */
            public string|null $current_user_email = null,
            /**
             * Current user name for a KeyNest device.
             */
            public string|null $current_user_name = null,
            /**
             * Current user phone number for a KeyNest device.
             */
            public string|null $current_user_phone_number = null,
            /**
             * Default office ID for a KeyNest device.
             */
            public float|null $default_office_id = null,
            /**
             * Device name for a KeyNest device.
             */
            public string|null $device_name = null,
            /**
             * Fob ID for a KeyNest device.
             */
            public float|null $fob_id = null,
            /**
             * Handover method for a KeyNest device.
             */
            public string|null $handover_method = null,
            /**
             * Whether the KeyNest device has a photo.
             */
            public bool|null $has_photo = null,
            /**
             * Whether the key is in a locker that does not support the access codes API.
             */
            public bool|null $is_quadient_locker = null,
            /**
             * Key ID for a KeyNest device.
             */
            public string|null $key_id = null,
            /**
             * Key notes for a KeyNest device.
             */
            public string|null $key_notes = null,
            /**
             * KeyNest app user for a KeyNest device.
             */
            public string|null $keynest_app_user = null,
            /**
             * Last movement timestamp for a KeyNest device.
             */
            public string|null $last_movement = null,
            /**
             * Property ID for a KeyNest device.
             */
            public string|null $property_id = null,
            /**
             * Property postcode for a KeyNest device.
             */
            public string|null $property_postcode = null,
            /**
             * Status type for a KeyNest device.
             */
            public string|null $status_type = null,
            /**
             * Subscription plan for a KeyNest device.
             */
            public string|null $subscription_plan = null,
        ) {}
    }

    /**
     * Metadata for a Kisi device.
     */
    class KisiMetadata
    {
        public static function from_json(mixed $json): KisiMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                description: $json->description ?? null,
                lock_id: $json->lock_id ?? null,
                lock_name: $json->lock_name ?? null,
                place_name: $json->place_name ?? null,
            );
        }

        public function __construct(
            /**
             * Description for a Kisi device.
             */
            public string|null $description = null,
            /**
             * Lock ID for a Kisi device.
             */
            public float|null $lock_id = null,
            /**
             * Lock name for a Kisi device.
             */
            public string|null $lock_name = null,
            /**
             * Place name for a Kisi device.
             */
            public string|null $place_name = null,
        ) {}
    }

    /**
     * Metadata for a Korelock device.
     */
    class KorelockMetadata
    {
        public static function from_json(mixed $json): KorelockMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                firmware_version: $json->firmware_version ?? null,
                location_id: $json->location_id ?? null,
                model_code: $json->model_code ?? null,
                serial_number: $json->serial_number ?? null,
                wifi_signal_strength: $json->wifi_signal_strength ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Korelock device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Korelock device.
             */
            public string|null $device_name = null,
            /**
             * Firmware version for a Korelock device.
             */
            public string|null $firmware_version = null,
            /**
             * Location ID for a Korelock device. Required for timebound access codes.
             */
            public string|null $location_id = null,
            /**
             * Model code for a Korelock device.
             */
            public string|null $model_code = null,
            /**
             * Serial number for a Korelock device.
             */
            public string|null $serial_number = null,
            /**
             * WiFi signal strength (0-1) for a Korelock device.
             */
            public float|null $wifi_signal_strength = null,
        ) {}
    }

    /**
     * Metadata for a Kwikset device.
     */
    class KwiksetMetadata
    {
        public static function from_json(mixed $json): KwiksetMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                model_number: $json->model_number ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Kwikset device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Kwikset device.
             */
            public string|null $device_name = null,
            /**
             * Model number for a Kwikset device.
             */
            public string|null $model_number = null,
        ) {}
    }

    /**
     * Metadata for a Lockly device.
     */
    class LocklyMetadata
    {
        public static function from_json(mixed $json): LocklyMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                model: $json->model ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Lockly device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Lockly device.
             */
            public string|null $device_name = null,
            /**
             * Model for a Lockly device.
             */
            public string|null $model = null,
        ) {}
    }

    /**
     * Metadata for a Minut device.
     */
    class MinutMetadata
    {
        public static function from_json(mixed $json): MinutMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                latest_sensor_values: isset($json->latest_sensor_values)
                    ? MinutMetadata\LatestSensorValues::from_json(
                        $json->latest_sensor_values,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Minut device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Minut device.
             */
            public string|null $device_name = null,
            /**
             * Latest sensor values for a Minut device.
             */
            public MinutMetadata\LatestSensorValues|null $latest_sensor_values = null,
        ) {}
    }

    /**
     * Metadata for a Google Nest device.
     */
    class NestMetadata
    {
        public static function from_json(mixed $json): NestMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_custom_name: $json->device_custom_name ?? null,
                device_name: $json->device_name ?? null,
                display_name: $json->display_name ?? null,
                nest_device_id: $json->nest_device_id ?? null,
                nest_structure_id: $json->nest_structure_id ?? null,
                structure_name: $json->structure_name ?? null,
            );
        }

        public function __construct(
            /**
             * Custom device name for a Google Nest device. The device owner sets this value.
             */
            public string|null $device_custom_name = null,
            /**
             * Device name for a Google Nest device. Google sets this value.
             */
            public string|null $device_name = null,
            /**
             * Display name for a Google Nest device.
             */
            public string|null $display_name = null,
            /**
             * Device ID for a Google Nest device.
             */
            public string|null $nest_device_id = null,
            /**
             * ID of the Google Nest structure containing the device.
             */
            public string|null $nest_structure_id = null,
            /**
             * Name of the Google Nest structure containing the device. The device owner sets this value.
             */
            public string|null $structure_name = null,
        ) {}
    }

    /**
     * Metadata for a NoiseAware device.
     */
    class NoiseawareMetadata
    {
        public static function from_json(mixed $json): NoiseawareMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_model: $json->device_model ?? null,
                device_name: $json->device_name ?? null,
                noise_level_decibel: $json->noise_level_decibel ?? null,
                noise_level_nrs: $json->noise_level_nrs ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a NoiseAware device.
             */
            public string|null $device_id = null,
            /**
             * Device model for a NoiseAware device.
             */
            public string|null $device_model = null,
            /**
             * Device name for a NoiseAware device.
             */
            public string|null $device_name = null,
            /**
             * Noise level, in decibels, for a NoiseAware device.
             */
            public float|null $noise_level_decibel = null,
            /**
             * Noise level, expressed as a Noise Risk Score (NRS), for a NoiseAware device.
             */
            public float|null $noise_level_nrs = null,
        ) {}
    }

    /**
     * Metadata for a Nuki device.
     */
    class NukiMetadata
    {
        public static function from_json(mixed $json): NukiMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                keypad_2_paired: $json->keypad_2_paired ?? null,
                keypad_battery_critical: $json->keypad_battery_critical ?? null,
                keypad_paired: $json->keypad_paired ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Nuki device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Nuki device.
             */
            public string|null $device_name = null,
            /**
             * Indicates whether keypad 2 is paired for a Nuki device.
             */
            public bool|null $keypad_2_paired = null,
            /**
             * Indicates whether the keypad battery is in a critical state for a Nuki device.
             */
            public bool|null $keypad_battery_critical = null,
            /**
             * Indicates whether the keypad is paired for a Nuki device.
             */
            public bool|null $keypad_paired = null,
        ) {}
    }

    /**
     * Metadata for an Omnitec device.
     */
    class OmnitecMetadata
    {
        public static function from_json(mixed $json): OmnitecMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                has_gateway: $json->has_gateway ?? null,
                lock_alias: $json->lock_alias ?? null,
                lock_id: $json->lock_id ?? null,
                lock_mac: $json->lock_mac ?? null,
                lock_name: $json->lock_name ?? null,
                time_zone: $json->time_zone ?? null,
                timezone_raw_offset_ms: $json->timezone_raw_offset_ms ?? null,
            );
        }

        public function __construct(
            /**
             * Whether the Omnitec lock has a connected gateway for remote operations.
             */
            public bool|null $has_gateway = null,
            /**
             * Operator-assigned alias for an Omnitec device.
             */
            public string|null $lock_alias = null,
            /**
             * Lock ID for an Omnitec device.
             */
            public float|null $lock_id = null,
            /**
             * Bluetooth MAC address for an Omnitec device.
             */
            public string|null $lock_mac = null,
            /**
             * Lock name for an Omnitec device.
             */
            public string|null $lock_name = null,
            /**
             * IANA time zone for the Omnitec device, used to schedule time-bound access codes at the correct local time (accounting for DST).
             */
            public string|null $time_zone = null,
            /**
             * Static UTC offset of the Omnitec lock in milliseconds. Does not account for DST.
             */
            public float|null $timezone_raw_offset_ms = null,
        ) {}
    }

    /**
     * Metadata for a Ring device.
     */
    class RingMetadata
    {
        public static function from_json(mixed $json): RingMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Ring device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Ring device.
             */
            public string|null $device_name = null,
        ) {}
    }

    /**
     * Metadata for a Salto KS device.
     */
    class SaltoKsMetadata
    {
        public static function from_json(mixed $json): SaltoKsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                battery_level: $json->battery_level ?? null,
                customer_reference: $json->customer_reference ?? null,
                has_custom_pin_subscription: $json->has_custom_pin_subscription ??
                    null,
                lock_id: $json->lock_id ?? null,
                lock_type: $json->lock_type ?? null,
                locked_state: $json->locked_state ?? null,
                model: $json->model ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
            );
        }

        public function __construct(
            /**
             * Battery level for a Salto KS device.
             */
            public string|null $battery_level = null,
            /**
             * Customer reference for a Salto KS device.
             */
            public string|null $customer_reference = null,
            /**
             * Indicates whether the site has a Salto KS subscription that supports custom PINs.
             */
            public bool|null $has_custom_pin_subscription = null,
            /**
             * Lock ID for a Salto KS device.
             */
            public string|null $lock_id = null,
            /**
             * Lock type for a Salto KS device.
             */
            public string|null $lock_type = null,
            /**
             * Locked state for a Salto KS device.
             */
            public string|null $locked_state = null,
            /**
             * Model for a Salto KS device.
             */
            public string|null $model = null,
            /**
             * Site ID for the Salto KS site to which the device belongs.
             */
            public string|null $site_id = null,
            /**
             * Site name for the Salto KS site to which the device belongs.
             */
            public string|null $site_name = null,
        ) {}
    }

    /**
     * Metada for a Salto device.
     *
     * @deprecated Use `salto_ks_metadata` instead.
     */
    class SaltoMetadata
    {
        public static function from_json(mixed $json): SaltoMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                battery_level: $json->battery_level ?? null,
                customer_reference: $json->customer_reference ?? null,
                lock_id: $json->lock_id ?? null,
                lock_type: $json->lock_type ?? null,
                locked_state: $json->locked_state ?? null,
                model: $json->model ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
            );
        }

        public function __construct(
            /**
             * Battery level for a Salto device.
             */
            public string|null $battery_level = null,
            /**
             * Customer reference for a Salto device.
             */
            public string|null $customer_reference = null,
            /**
             * Lock ID for a Salto device.
             */
            public string|null $lock_id = null,
            /**
             * Lock type for a Salto device.
             */
            public string|null $lock_type = null,
            /**
             * Locked state for a Salto device.
             */
            public string|null $locked_state = null,
            /**
             * Model for a Salto device.
             */
            public string|null $model = null,
            /**
             * Site ID for the Salto KS site to which the device belongs.
             */
            public string|null $site_id = null,
            /**
             * Site name for the Salto KS site to which the device belongs.
             */
            public string|null $site_name = null,
        ) {}
    }

    /**
     * Metadata for a Schlage device.
     */
    class SchlageMetadata
    {
        public static function from_json(mixed $json): SchlageMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                model: $json->model ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Schlage device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Schlage device.
             */
            public string|null $device_name = null,
            /**
             * Model for a Schlage device.
             */
            public string|null $model = null,
        ) {}
    }

    /**
     * Metadata for Seam Bridge.
     */
    class SeamBridgeMetadata
    {
        public static function from_json(mixed $json): SeamBridgeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_num: $json->device_num ?? null,
                name: $json->name ?? null,
                unlock_method: $json->unlock_method ?? null,
            );
        }

        public function __construct(
            /**
             * Device number for Seam Bridge.
             */
            public float|null $device_num = null,
            /**
             * Name for Seam Bridge.
             */
            public string|null $name = null,
            /**
             * Unlock method for Seam Bridge.
             */
            public string|null $unlock_method = null,
        ) {}
    }

    /**
     * Metadata for a Sensi device.
     */
    class SensiMetadata
    {
        public static function from_json(mixed $json): SensiMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                dual_setpoints_not_supported: $json->dual_setpoints_not_supported ??
                    null,
                enforced_setpoint_range_celsius: $json->enforced_setpoint_range_celsius ??
                    null,
                product_type: $json->product_type ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Sensi device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Sensi device.
             */
            public string|null $device_name = null,
            /**
             * Set to true when the device does not support the /dual-setpoints API endpoint.
             */
            public bool|null $dual_setpoints_not_supported = null,
            /**
             * Enforced setpoint range in Celsius for a Sensi device, derived from an OutOfRange API error.
             */
            public array|null $enforced_setpoint_range_celsius = null,
            /**
             * Product type for a Sensi device.
             */
            public string|null $product_type = null,
        ) {}
    }

    /**
     * Metadata for a SmartThings device.
     */
    class SmartthingsMetadata
    {
        public static function from_json(mixed $json): SmartthingsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                location_id: $json->location_id ?? null,
                model: $json->model ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a SmartThings device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a SmartThings device.
             */
            public string|null $device_name = null,
            /**
             * Location ID for a SmartThings device.
             */
            public string|null $location_id = null,
            /**
             * Model for a SmartThings device.
             */
            public string|null $model = null,
        ) {}
    }

    /**
     * Metadata for a tado° device.
     */
    class TadoMetadata
    {
        public static function from_json(mixed $json): TadoMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_type: $json->device_type ?? null,
                serial_no: $json->serial_no ?? null,
            );
        }

        public function __construct(
            /**
             * Device type for a tado° device.
             */
            public string|null $device_type = null,
            /**
             * Serial number for a tado° device.
             */
            public string|null $serial_no = null,
        ) {}
    }

    /**
     * Metadata for a Tedee device.
     */
    class TedeeMetadata
    {
        public static function from_json(mixed $json): TedeeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                bridge_id: $json->bridge_id ?? null,
                bridge_name: $json->bridge_name ?? null,
                device_id: $json->device_id ?? null,
                device_model: $json->device_model ?? null,
                device_name: $json->device_name ?? null,
                keypad_id: $json->keypad_id ?? null,
                serial_number: $json->serial_number ?? null,
            );
        }

        public function __construct(
            /**
             * Bridge ID for a Tedee device.
             */
            public float|null $bridge_id = null,
            /**
             * Bridge name for a Tedee device.
             */
            public string|null $bridge_name = null,
            /**
             * Device ID for a Tedee device.
             */
            public float|null $device_id = null,
            /**
             * Device model for a Tedee device.
             */
            public string|null $device_model = null,
            /**
             * Device name for a Tedee device.
             */
            public string|null $device_name = null,
            /**
             * Keypad ID for a Tedee device.
             */
            public float|null $keypad_id = null,
            /**
             * Serial number for a Tedee device.
             */
            public string|null $serial_number = null,
        ) {}
    }

    /**
     * Metadata for a TTLock device.
     */
    class TtlockMetadata
    {
        public static function from_json(mixed $json): TtlockMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                feature_value: $json->feature_value ?? null,
                features: isset($json->features)
                    ? TtlockMetadata\Features::from_json($json->features)
                    : null,
                has_gateway: $json->has_gateway ?? null,
                lock_alias: $json->lock_alias ?? null,
                lock_id: $json->lock_id ?? null,
                timezone_raw_offset_ms: $json->timezone_raw_offset_ms ?? null,
                wireless_keypads: array_map(
                    fn($w) => TtlockMetadata\WirelessKeypads::from_json($w),
                    $json->wireless_keypads ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Feature value for a TTLock device.
             */
            public string|null $feature_value = null,
            /**
             * Features for a TTLock device.
             */
            public TtlockMetadata\Features|null $features = null,
            /**
             * Indicates whether a TTLock device has a gateway.
             */
            public bool|null $has_gateway = null,
            /**
             * Lock alias for a TTLock device.
             */
            public string|null $lock_alias = null,
            /**
             * Lock ID for a TTLock device.
             */
            public float|null $lock_id = null,
            /**
             * Lock-side timezone offset in milliseconds east of UTC, as configured in the TTLock app. Source of truth for the lock's wall-clock interpretation of access code start/end times — a misconfigured value here is the typical cause of customer "codes offset by N hours" reports. Diagnostic only; Seam does not convert times based on this value.
             */
            public float|null $timezone_raw_offset_ms = null,
            /**
             * Wireless keypads for a TTLock device.
             */
            public array|null $wireless_keypads = null,
        ) {}
    }

    /**
     * Metadata for a 2N device.
     */
    class TwoNMetadata
    {
        public static function from_json(mixed $json): TwoNMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a 2N device.
             */
            public float|null $device_id = null,
            /**
             * Device name for a 2N device.
             */
            public string|null $device_name = null,
        ) {}
    }

    /**
     * Metadata for an Ultraloq device.
     */
    class UltraloqMetadata
    {
        public static function from_json(mixed $json): UltraloqMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                device_type: $json->device_type ?? null,
                time_zone: $json->time_zone ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for an Ultraloq device.
             */
            public string|null $device_id = null,
            /**
             * Device name for an Ultraloq device.
             */
            public string|null $device_name = null,
            /**
             * Device type for an Ultraloq device.
             */
            public string|null $device_type = null,
            /**
             * IANA timezone for the Ultraloq device.
             */
            public string|null $time_zone = null,
        ) {}
    }

    /**
     * Metadata for an ASSA ABLOY Visionline system.
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(encoder_id: $json->encoder_id ?? null);
        }

        public function __construct(
            /**
             * Encoder ID for an ASSA ABLOY Visionline system.
             */
            public string|null $encoder_id = null,
        ) {}
    }

    /**
     * Metadata for a Wyze device.
     */
    class WyzeMetadata
    {
        public static function from_json(mixed $json): WyzeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_info_model: $json->device_info_model ?? null,
                device_name: $json->device_name ?? null,
                keypad_uuid: $json->keypad_uuid ?? null,
                locker_status_hardlock: $json->locker_status_hardlock ?? null,
                product_model: $json->product_model ?? null,
                product_name: $json->product_name ?? null,
                product_type: $json->product_type ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Wyze device.
             */
            public string|null $device_id = null,
            /**
             * Device information model for a Wyze device.
             */
            public string|null $device_info_model = null,
            /**
             * Device name for a Wyze device.
             */
            public string|null $device_name = null,
            /**
             * Keypad UUID for a Wyze device.
             */
            public string|null $keypad_uuid = null,
            /**
             * Locker status (hardlock) for a Wyze device.
             */
            public float|null $locker_status_hardlock = null,
            /**
             * Product model for a Wyze device.
             */
            public string|null $product_model = null,
            /**
             * Product name for a Wyze device.
             */
            public string|null $product_name = null,
            /**
             * Product type for a Wyze device.
             */
            public string|null $product_type = null,
        ) {}
    }

    /**
     * Metadata for a Yacan device.
     */
    class YacanMetadata
    {
        public static function from_json(mixed $json): YacanMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                device_type: $json->device_type ?? null,
                serial_number: $json->serial_number ?? null,
            );
        }

        public function __construct(
            /**
             * Device ID for a Yacan device.
             */
            public string|null $device_id = null,
            /**
             * Device name for a Yacan device.
             */
            public string|null $device_name = null,
            /**
             * Device type for a Yacan device.
             */
            public string|null $device_type = null,
            /**
             * Serial number for a Yacan device.
             */
            public string|null $serial_number = null,
        ) {}
    }

    /**
     * Constraints on access codes for the device. Seam represents each constraint as an object with a `constraint_type` property. Depending on the constraint type, there may also be additional properties. Note that some constraints are manufacturer- or device-specific.
     */
    class CodeConstraints
    {
        public static function from_json(mixed $json): CodeConstraints|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                constraint_type: $json->constraint_type ?? null,
                max_length: $json->max_length ?? null,
                min_length: $json->min_length ?? null,
            );
        }

        public function __construct(
            public string|null $constraint_type,
            /**
             * Maximum name length constraint for access codes.
             */
            public float|null $max_length = null,
            /**
             * Minimum name length constraint for access codes.
             */
            public float|null $min_length = null,
        ) {}
    }

    /**
     * Keypad battery status.
     */
    class KeypadBattery
    {
        public static function from_json(mixed $json): KeypadBattery|null
        {
            if (!$json) {
                return null;
            }
            return new self(level: $json->level ?? null);
        }

        public function __construct(
            /**
             * Keypad battery charge level.
             */
            public float|null $level,
        ) {}
    }

    /**
     * Time frames that may be requested when creating an offline access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
     */
    class OfflineTimeFrameOptions
    {
        public static function from_json(
            mixed $json,
        ): OfflineTimeFrameOptions|null {
            if (!$json) {
                return null;
            }
            return new self(
                display_name: $json->display_name ?? null,
                end_date_recurrence_rule: $json->end_date_recurrence_rule ??
                    null,
                matching_start_end_time: $json->matching_start_end_time ?? null,
                max_duration: $json->max_duration ?? null,
                min_duration: $json->min_duration ?? null,
                start_date_recurrence_rule: $json->start_date_recurrence_rule ??
                    null,
                time_pairs: array_map(
                    fn($t) => OfflineTimeFrameOptions\TimePairs::from_json($t),
                    $json->time_pairs ?? [],
                ),
                time_zone: $json->time_zone ?? null,
            );
        }

        public function __construct(
            /**
             * Label for this option. For a single-option device, the product name (for example, `algoPIN` or `SmartPIN`); for a multi-option device, a label that distinguishes it (for example, `Hourly` or `Fixed start times`).
             */
            public string|null $display_name,
            /**
             * iCalendar recurrence rule (RRULE) that the end date must fall on. Constrains which calendar dates are selectable, independent of the time-of-day rules.
             */
            public string|null $end_date_recurrence_rule = null,
            /**
             * When `true`, the start and end must fall at the same time of day (the caller picks which). Mutually exclusive with `time_pairs`.
             */
            public true|null $matching_start_end_time = null,
            /**
             * Maximum duration this option covers, as an ISO 8601 duration (for example, `PT672H` or `P367D`). Omitted when there is no maximum.
             */
            public string|null $max_duration = null,
            /**
             * Minimum duration this option covers, as an ISO 8601 duration (for example, `PT1H` or `P29D`). Omitted when there is no minimum.
             */
            public string|null $min_duration = null,
            /**
             * iCalendar recurrence rule (RRULE) that the start date must fall on (for example, `FREQ=MONTHLY;BYDAY=1MO,3MO`). Constrains which calendar dates are selectable, independent of the time-of-day rules.
             */
            public string|null $start_date_recurrence_rule = null,
            /**
             * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
             */
            public array|null $time_pairs = null,
            /**
             * IANA time zone for interpreting `time_pairs` and the date recurrence rules. Present only when the option fixes times or dates.
             */
            public string|null $time_zone = null,
        ) {}
    }

    /**
     * Time frames that may be requested when creating an online access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
     */
    class OnlineTimeFrameOptions
    {
        public static function from_json(
            mixed $json,
        ): OnlineTimeFrameOptions|null {
            if (!$json) {
                return null;
            }
            return new self(
                display_name: $json->display_name ?? null,
                end_date_recurrence_rule: $json->end_date_recurrence_rule ??
                    null,
                matching_start_end_time: $json->matching_start_end_time ?? null,
                max_duration: $json->max_duration ?? null,
                min_duration: $json->min_duration ?? null,
                start_date_recurrence_rule: $json->start_date_recurrence_rule ??
                    null,
                time_pairs: array_map(
                    fn($t) => OnlineTimeFrameOptions\TimePairs::from_json($t),
                    $json->time_pairs ?? [],
                ),
                time_zone: $json->time_zone ?? null,
            );
        }

        public function __construct(
            /**
             * Label for this option. For a single-option device, the product name (for example, `algoPIN` or `SmartPIN`); for a multi-option device, a label that distinguishes it (for example, `Hourly` or `Fixed start times`).
             */
            public string|null $display_name,
            /**
             * iCalendar recurrence rule (RRULE) that the end date must fall on. Constrains which calendar dates are selectable, independent of the time-of-day rules.
             */
            public string|null $end_date_recurrence_rule = null,
            /**
             * When `true`, the start and end must fall at the same time of day (the caller picks which). Mutually exclusive with `time_pairs`.
             */
            public true|null $matching_start_end_time = null,
            /**
             * Maximum duration this option covers, as an ISO 8601 duration (for example, `PT672H` or `P367D`). Omitted when there is no maximum.
             */
            public string|null $max_duration = null,
            /**
             * Minimum duration this option covers, as an ISO 8601 duration (for example, `PT1H` or `P29D`). Omitted when there is no minimum.
             */
            public string|null $min_duration = null,
            /**
             * iCalendar recurrence rule (RRULE) that the start date must fall on (for example, `FREQ=MONTHLY;BYDAY=1MO,3MO`). Constrains which calendar dates are selectable, independent of the time-of-day rules.
             */
            public string|null $start_date_recurrence_rule = null,
            /**
             * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
             */
            public array|null $time_pairs = null,
            /**
             * IANA time zone for interpreting `time_pairs` and the date recurrence rules. Present only when the option fixes times or dates.
             */
            public string|null $time_zone = null,
        ) {}
    }

    /**
     * Active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     *
     * @deprecated Use `active_thermostat_schedule_id` with `/thermostats/schedules/get` instead.
     */
    class ActiveThermostatSchedule
    {
        public static function from_json(
            mixed $json,
        ): ActiveThermostatSchedule|null {
            if (!$json) {
                return null;
            }
            return new self(
                climate_preset_key: $json->climate_preset_key ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => ActiveThermostatSchedule\Errors::from_json($e),
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
            /**
             * Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $climate_preset_key,
            /**
             * Date and time at which the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) was created.
             */
            public string|null $created_at,
            /**
             * ID of the desired [thermostat](https://docs.seam.co/capability-guides/thermostats) device.
             */
            public string|null $device_id,
            /**
             * Date and time at which the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $ends_at,
            /**
             * Errors associated with the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public array $errors,
            /**
             * Indicates whether a person at the thermostat can change the thermostat's settings after the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) starts.
             */
            public bool|null $is_override_allowed = null,
            /**
             * Number of minutes for which a person at the thermostat can change the thermostat's settings after the activation of the scheduled [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets). See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
             */
            public int|null $max_override_period_minutes = null,
            /**
             * User-friendly name to identify the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $name,
            /**
             * Date and time at which the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at,
            /**
             * ID of the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
             */
            public string|null $thermostat_schedule_id,
            /**
             * ID of the workspace that contains the thermostat schedule.
             */
            public string|null $workspace_id,
        ) {}
    }

    /**
     * Available [climate presets](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for the thermostat.
     */
    class AvailableClimatePresets
    {
        public static function from_json(
            mixed $json,
        ): AvailableClimatePresets|null {
            if (!$json) {
                return null;
            }
            return new self(
                can_delete: $json->can_delete ?? null,
                can_edit: $json->can_edit ?? null,
                can_use_with_thermostat_daily_programs: $json->can_use_with_thermostat_daily_programs ??
                    null,
                climate_preset_key: $json->climate_preset_key ?? null,
                climate_preset_mode: $json->climate_preset_mode ?? null,
                cooling_set_point_celsius: $json->cooling_set_point_celsius ??
                    null,
                cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                    null,
                display_name: $json->display_name ?? null,
                ecobee_metadata: isset($json->ecobee_metadata)
                    ? AvailableClimatePresets\EcobeeMetadata::from_json(
                        $json->ecobee_metadata,
                    )
                    : null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                heating_set_point_celsius: $json->heating_set_point_celsius ??
                    null,
                heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                    null,
                hvac_mode_setting: $json->hvac_mode_setting ?? null,
                manual_override_allowed: $json->manual_override_allowed ?? null,
                name: $json->name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be deleted.
             */
            public bool|null $can_delete,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be edited.
             */
            public bool|null $can_edit,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be programmed in a thermostat daily program.
             */
            public bool|null $can_use_with_thermostat_daily_programs,
            /**
             * Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $climate_preset_key,
            /**
             * The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
             */
            public string|null $climate_preset_mode = null,
            /**
             * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_fahrenheit = null,
            /**
             * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $display_name,
            /**
             * Metadata specific to the Ecobee climate, if applicable.
             */
            public AvailableClimatePresets\EcobeeMetadata|null $ecobee_metadata = null,
            /**
             * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
             */
            public string|null $fan_mode_setting = null,
            /**
             * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_fahrenheit = null,
            /**
             * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
             */
            public string|null $hvac_mode_setting = null,
            /**
             * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
             *
             * @deprecated Use 'thermostat_schedule.is_override_allowed'
             */
            public bool|null $manual_override_allowed,
            /**
             * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $name = null,
        ) {}
    }

    /**
     * Current climate setting.
     */
    class CurrentClimateSetting
    {
        public static function from_json(
            mixed $json,
        ): CurrentClimateSetting|null {
            if (!$json) {
                return null;
            }
            return new self(
                can_delete: $json->can_delete ?? null,
                can_edit: $json->can_edit ?? null,
                can_use_with_thermostat_daily_programs: $json->can_use_with_thermostat_daily_programs ??
                    null,
                climate_preset_key: $json->climate_preset_key ?? null,
                climate_preset_mode: $json->climate_preset_mode ?? null,
                cooling_set_point_celsius: $json->cooling_set_point_celsius ??
                    null,
                cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                    null,
                display_name: $json->display_name ?? null,
                ecobee_metadata: isset($json->ecobee_metadata)
                    ? CurrentClimateSetting\EcobeeMetadata::from_json(
                        $json->ecobee_metadata,
                    )
                    : null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                heating_set_point_celsius: $json->heating_set_point_celsius ??
                    null,
                heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                    null,
                hvac_mode_setting: $json->hvac_mode_setting ?? null,
                manual_override_allowed: $json->manual_override_allowed ?? null,
                name: $json->name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be deleted.
             */
            public bool|null $can_delete = null,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be edited.
             */
            public bool|null $can_edit = null,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be programmed in a thermostat daily program.
             */
            public bool|null $can_use_with_thermostat_daily_programs = null,
            /**
             * Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $climate_preset_key = null,
            /**
             * The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
             */
            public string|null $climate_preset_mode = null,
            /**
             * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_fahrenheit = null,
            /**
             * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $display_name = null,
            /**
             * Metadata specific to the Ecobee climate, if applicable.
             */
            public CurrentClimateSetting\EcobeeMetadata|null $ecobee_metadata = null,
            /**
             * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
             */
            public string|null $fan_mode_setting = null,
            /**
             * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_fahrenheit = null,
            /**
             * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
             */
            public string|null $hvac_mode_setting = null,
            /**
             * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
             *
             * @deprecated Use 'thermostat_schedule.is_override_allowed'
             */
            public bool|null $manual_override_allowed = null,
            /**
             * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $name = null,
        ) {}
    }

    /**
     * @deprecated use fallback_climate_preset_key to specify a fallback climate preset instead.
     */
    class DefaultClimateSetting
    {
        public static function from_json(
            mixed $json,
        ): DefaultClimateSetting|null {
            if (!$json) {
                return null;
            }
            return new self(
                can_delete: $json->can_delete ?? null,
                can_edit: $json->can_edit ?? null,
                can_use_with_thermostat_daily_programs: $json->can_use_with_thermostat_daily_programs ??
                    null,
                climate_preset_key: $json->climate_preset_key ?? null,
                climate_preset_mode: $json->climate_preset_mode ?? null,
                cooling_set_point_celsius: $json->cooling_set_point_celsius ??
                    null,
                cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                    null,
                display_name: $json->display_name ?? null,
                ecobee_metadata: isset($json->ecobee_metadata)
                    ? DefaultClimateSetting\EcobeeMetadata::from_json(
                        $json->ecobee_metadata,
                    )
                    : null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                heating_set_point_celsius: $json->heating_set_point_celsius ??
                    null,
                heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                    null,
                hvac_mode_setting: $json->hvac_mode_setting ?? null,
                manual_override_allowed: $json->manual_override_allowed ?? null,
                name: $json->name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be deleted.
             */
            public bool|null $can_delete = null,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be edited.
             */
            public bool|null $can_edit = null,
            /**
             * Indicates whether the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) key can be programmed in a thermostat daily program.
             */
            public bool|null $can_use_with_thermostat_daily_programs = null,
            /**
             * Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $climate_preset_key = null,
            /**
             * The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
             */
            public string|null $climate_preset_mode = null,
            /**
             * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_fahrenheit = null,
            /**
             * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $display_name = null,
            /**
             * Metadata specific to the Ecobee climate, if applicable.
             */
            public DefaultClimateSetting\EcobeeMetadata|null $ecobee_metadata = null,
            /**
             * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
             */
            public string|null $fan_mode_setting = null,
            /**
             * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_fahrenheit = null,
            /**
             * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
             */
            public string|null $hvac_mode_setting = null,
            /**
             * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
             *
             * @deprecated Use 'thermostat_schedule.is_override_allowed'
             */
            public bool|null $manual_override_allowed = null,
            /**
             * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
             */
            public string|null $name = null,
        ) {}
    }

    /**
     * Current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
     */
    class TemperatureThreshold
    {
        public static function from_json(mixed $json): TemperatureThreshold|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                lower_limit_celsius: $json->lower_limit_celsius ?? null,
                lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
                upper_limit_celsius: $json->upper_limit_celsius ?? null,
                upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null,
            );
        }

        public function __construct(
            /**
             * Lower limit in °C within the current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
             */
            public float|null $lower_limit_celsius,
            /**
             * Lower limit in °F within the current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
             */
            public float|null $lower_limit_fahrenheit,
            /**
             * Upper limit in °C within the current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
             */
            public float|null $upper_limit_celsius,
            /**
             * Upper limit in °F within the current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
             */
            public float|null $upper_limit_fahrenheit,
        ) {}
    }

    /**
     * Configured [daily programs](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
     */
    class ThermostatDailyPrograms
    {
        public static function from_json(
            mixed $json,
        ): ThermostatDailyPrograms|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                name: $json->name ?? null,
                periods: array_map(
                    fn($p) => ThermostatDailyPrograms\Periods::from_json($p),
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

    /**
     * Current [weekly program](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
     */
    class ThermostatWeeklyProgram
    {
        public static function from_json(
            mixed $json,
        ): ThermostatWeeklyProgram|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                friday_program_id: $json->friday_program_id ?? null,
                monday_program_id: $json->monday_program_id ?? null,
                saturday_program_id: $json->saturday_program_id ?? null,
                sunday_program_id: $json->sunday_program_id ?? null,
                thursday_program_id: $json->thursday_program_id ?? null,
                tuesday_program_id: $json->tuesday_program_id ?? null,
                wednesday_program_id: $json->wednesday_program_id ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the thermostat weekly program was created.
             */
            public string|null $created_at,
            /**
             * ID of the thermostat daily program to run on Fridays.
             */
            public string|null $friday_program_id,
            /**
             * ID of the thermostat daily program to run on Mondays.
             */
            public string|null $monday_program_id,
            /**
             * ID of the thermostat daily program to run on Saturdays.
             */
            public string|null $saturday_program_id,
            /**
             * ID of the thermostat daily program to run on Sundays.
             */
            public string|null $sunday_program_id,
            /**
             * ID of the thermostat daily program to run on Thursdays.
             */
            public string|null $thursday_program_id,
            /**
             * ID of the thermostat daily program to run on Tuesdays.
             */
            public string|null $tuesday_program_id,
            /**
             * ID of the thermostat daily program to run on Wednesdays.
             */
            public string|null $wednesday_program_id,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\AccessoryKeypad {
    /**
     * Keypad battery properties.
     */
    class Battery
    {
        public static function from_json(mixed $json): Battery|null
        {
            if (!$json) {
                return null;
            }
            return new self(level: $json->level ?? null);
        }

        public function __construct(public float|null $level) {}
    }
}

namespace Seam\Resources\Device\Properties\AssaAbloyCredentialServiceMetadata {
    /**
     * Endpoints associated with the phone.
     */
    class Endpoints
    {
        public static function from_json(mixed $json): Endpoints|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                endpoint_id: $json->endpoint_id ?? null,
                is_active: $json->is_active ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the associated endpoint.
             */
            public string|null $endpoint_id = null,
            /**
             * Indicated whether the endpoint is active.
             */
            public bool|null $is_active = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\DormakabaOracodeMetadata {
    /**
     * Predefined time slots for a dormakaba Oracode device.
     */
    class PredefinedTimeSlots
    {
        public static function from_json(mixed $json): PredefinedTimeSlots|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                check_in_time: $json->check_in_time ?? null,
                check_out_time: $json->check_out_time ?? null,
                dormakaba_oracode_user_level_id: $json->dormakaba_oracode_user_level_id ??
                    null,
                dormakaba_oracode_user_level_prefix: $json->dormakaba_oracode_user_level_prefix ??
                    null,
                is_24_hour: $json->is_24_hour ?? null,
                is_biweekly_mode: $json->is_biweekly_mode ?? null,
                is_master: $json->is_master ?? null,
                is_one_shot: $json->is_one_shot ?? null,
                name: $json->name ?? null,
                prefix: $json->prefix ?? null,
            );
        }

        public function __construct(
            /**
             * Check in time for a time slot for a dormakaba Oracode device.
             */
            public string|null $check_in_time = null,
            /**
             * Checkout time for a time slot for a dormakaba Oracode device.
             */
            public string|null $check_out_time = null,
            /**
             * ID of a user level for a dormakaba Oracode device.
             */
            public string|null $dormakaba_oracode_user_level_id = null,
            /**
             * Prefix for a user level for a dormakaba Oracode device.
             */
            public float|null $dormakaba_oracode_user_level_prefix = null,
            /**
             * Indicates whether a time slot for a dormakaba Oracode device is a 24-hour time slot.
             */
            public bool|null $is_24_hour = null,
            /**
             * Indicates whether a time slot for a dormakaba Oracode device is in biweekly mode.
             */
            public bool|null $is_biweekly_mode = null,
            /**
             * Indicates whether a time slot for a dormakaba Oracode device is a master time slot.
             */
            public bool|null $is_master = null,
            /**
             * Indicates whether a time slot for a dormakaba Oracode device is a one-shot time slot.
             */
            public bool|null $is_one_shot = null,
            /**
             * Name of a time slot for a dormakaba Oracode device.
             */
            public string|null $name = null,
            /**
             * Prefix for a time slot for a dormakaba Oracode device.
             */
            public float|null $prefix = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\MinutMetadata {
    /**
     * Latest sensor values for a Minut device.
     */
    class LatestSensorValues
    {
        public static function from_json(mixed $json): LatestSensorValues|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                accelerometer_z: isset($json->accelerometer_z)
                    ? LatestSensorValues\AccelerometerZ::from_json(
                        $json->accelerometer_z,
                    )
                    : null,
                humidity: isset($json->humidity)
                    ? LatestSensorValues\Humidity::from_json($json->humidity)
                    : null,
                pressure: isset($json->pressure)
                    ? LatestSensorValues\Pressure::from_json($json->pressure)
                    : null,
                sound: isset($json->sound)
                    ? LatestSensorValues\Sound::from_json($json->sound)
                    : null,
                temperature: isset($json->temperature)
                    ? LatestSensorValues\Temperature::from_json(
                        $json->temperature,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Latest accelerometer Z-axis reading for a Minut device.
             */
            public LatestSensorValues\AccelerometerZ|null $accelerometer_z = null,
            /**
             * Latest humidity reading for a Minut device.
             */
            public LatestSensorValues\Humidity|null $humidity = null,
            /**
             * Latest pressure reading for a Minut device.
             */
            public LatestSensorValues\Pressure|null $pressure = null,
            /**
             * Latest sound reading for a Minut device.
             */
            public LatestSensorValues\Sound|null $sound = null,
            /**
             * Latest temperature reading for a Minut device.
             */
            public LatestSensorValues\Temperature|null $temperature = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\MinutMetadata\LatestSensorValues {
    /**
     * Latest accelerometer Z-axis reading for a Minut device.
     */
    class AccelerometerZ
    {
        public static function from_json(mixed $json): AccelerometerZ|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                time: $json->time ?? null,
                value: $json->value ?? null,
            );
        }

        public function __construct(
            /**
             * Time of latest accelerometer Z-axis reading for a Minut device.
             */
            public string|null $time = null,
            /**
             * Value of latest accelerometer Z-axis reading for a Minut device.
             */
            public float|null $value = null,
        ) {}
    }

    /**
     * Latest humidity reading for a Minut device.
     */
    class Humidity
    {
        public static function from_json(mixed $json): Humidity|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                time: $json->time ?? null,
                value: $json->value ?? null,
            );
        }

        public function __construct(
            /**
             * Time of latest humidity reading for a Minut device.
             */
            public string|null $time = null,
            /**
             * Value of latest humidity reading for a Minut device.
             */
            public float|null $value = null,
        ) {}
    }

    /**
     * Latest pressure reading for a Minut device.
     */
    class Pressure
    {
        public static function from_json(mixed $json): Pressure|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                time: $json->time ?? null,
                value: $json->value ?? null,
            );
        }

        public function __construct(
            /**
             * Time of latest pressure reading for a Minut device.
             */
            public string|null $time = null,
            /**
             * Value of latest pressure reading for a Minut device.
             */
            public float|null $value = null,
        ) {}
    }

    /**
     * Latest sound reading for a Minut device.
     */
    class Sound
    {
        public static function from_json(mixed $json): Sound|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                time: $json->time ?? null,
                value: $json->value ?? null,
            );
        }

        public function __construct(
            /**
             * Time of latest sound reading for a Minut device.
             */
            public string|null $time = null,
            /**
             * Value of latest sound reading for a Minut device.
             */
            public float|null $value = null,
        ) {}
    }

    /**
     * Latest temperature reading for a Minut device.
     */
    class Temperature
    {
        public static function from_json(mixed $json): Temperature|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                time: $json->time ?? null,
                value: $json->value ?? null,
            );
        }

        public function __construct(
            /**
             * Time of latest temperature reading for a Minut device.
             */
            public string|null $time = null,
            /**
             * Value of latest temperature reading for a Minut device.
             */
            public float|null $value = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\TtlockMetadata {
    /**
     * Features for a TTLock device.
     */
    class Features
    {
        public static function from_json(mixed $json): Features|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                auto_lock_time_config: $json->auto_lock_time_config ?? null,
                incomplete_keyboard_passcode: $json->incomplete_keyboard_passcode ??
                    null,
                lock_command: $json->lock_command ?? null,
                passcode: $json->passcode ?? null,
                passcode_management: $json->passcode_management ?? null,
                unlock_via_gateway: $json->unlock_via_gateway ?? null,
                wifi: $json->wifi ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether a TTLock device supports auto-lock time configuration.
             */
            public bool|null $auto_lock_time_config = null,
            /**
             * Indicates whether a TTLock device supports an incomplete keyboard passcode.
             */
            public bool|null $incomplete_keyboard_passcode = null,
            /**
             * Indicates whether a TTLock device supports the lock command.
             */
            public bool|null $lock_command = null,
            /**
             * Indicates whether a TTLock device supports a passcode.
             */
            public bool|null $passcode = null,
            /**
             * Indicates whether a TTLock device supports passcode management.
             */
            public bool|null $passcode_management = null,
            /**
             * Indicates whether a TTLock device supports unlock via gateway.
             */
            public bool|null $unlock_via_gateway = null,
            /**
             * Indicates whether a TTLock device supports Wi-Fi.
             */
            public bool|null $wifi = null,
        ) {}
    }

    /**
     * Wireless keypads for a TTLock device.
     */
    class WirelessKeypads
    {
        public static function from_json(mixed $json): WirelessKeypads|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                wireless_keypad_id: $json->wireless_keypad_id ?? null,
                wireless_keypad_name: $json->wireless_keypad_name ?? null,
            );
        }

        public function __construct(
            /**
             * ID for a wireless keypad for a TTLock device.
             */
            public float|null $wireless_keypad_id = null,
            /**
             * Name for a wireless keypad for a TTLock device.
             */
            public string|null $wireless_keypad_name = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\OfflineTimeFrameOptions {
    /**
     * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
     */
    class TimePairs
    {
        public static function from_json(mixed $json): TimePairs|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                display_name: $json->display_name ?? null,
                end_time: $json->end_time ?? null,
                start_time: $json->start_time ?? null,
            );
        }

        public function __construct(
            /**
             * Label for the start/end time pairing.
             */
            public string|null $display_name,
            /**
             * End time of day as a 24-hour `HH:MM` value, interpreted in the option's `time_zone`. An `end_time` earlier on the clock than `start_time` means the end falls on a later date.
             */
            public string|null $end_time,
            /**
             * Start time of day as a 24-hour `HH:MM` value, interpreted in the option's `time_zone`.
             */
            public string|null $start_time,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\OnlineTimeFrameOptions {
    /**
     * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
     */
    class TimePairs
    {
        public static function from_json(mixed $json): TimePairs|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                display_name: $json->display_name ?? null,
                end_time: $json->end_time ?? null,
                start_time: $json->start_time ?? null,
            );
        }

        public function __construct(
            /**
             * Label for the start/end time pairing.
             */
            public string|null $display_name,
            /**
             * End time of day as a 24-hour `HH:MM` value, interpreted in the option's `time_zone`. An `end_time` earlier on the clock than `start_time` means the end falls on a later date.
             */
            public string|null $end_time,
            /**
             * Start time of day as a 24-hour `HH:MM` value, interpreted in the option's `time_zone`.
             */
            public string|null $start_time,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\ActiveThermostatSchedule {
    /**
     * Errors associated with the [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
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
}

namespace Seam\Resources\Device\Properties\AvailableClimatePresets {
    /**
     * Metadata specific to the Ecobee climate, if applicable.
     */
    class EcobeeMetadata
    {
        public static function from_json(mixed $json): EcobeeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                climate_ref: $json->climate_ref ?? null,
                is_optimized: $json->is_optimized ?? null,
                owner: $json->owner ?? null,
            );
        }

        public function __construct(
            /**
             * Reference to the Ecobee climate, if applicable.
             */
            public string|null $climate_ref = null,
            /**
             * Indicates if the climate preset is optimized by Ecobee.
             */
            public bool|null $is_optimized = null,
            /**
             * Indicates whether the climate preset is owned by the user or the system.
             */
            public string|null $owner = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\CurrentClimateSetting {
    /**
     * Metadata specific to the Ecobee climate, if applicable.
     */
    class EcobeeMetadata
    {
        public static function from_json(mixed $json): EcobeeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                climate_ref: $json->climate_ref ?? null,
                is_optimized: $json->is_optimized ?? null,
                owner: $json->owner ?? null,
            );
        }

        public function __construct(
            /**
             * Reference to the Ecobee climate, if applicable.
             */
            public string|null $climate_ref = null,
            /**
             * Indicates if the climate preset is optimized by Ecobee.
             */
            public bool|null $is_optimized = null,
            /**
             * Indicates whether the climate preset is owned by the user or the system.
             */
            public string|null $owner = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\DefaultClimateSetting {
    /**
     * Metadata specific to the Ecobee climate, if applicable.
     */
    class EcobeeMetadata
    {
        public static function from_json(mixed $json): EcobeeMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                climate_ref: $json->climate_ref ?? null,
                is_optimized: $json->is_optimized ?? null,
                owner: $json->owner ?? null,
            );
        }

        public function __construct(
            /**
             * Reference to the Ecobee climate, if applicable.
             */
            public string|null $climate_ref = null,
            /**
             * Indicates if the climate preset is optimized by Ecobee.
             */
            public bool|null $is_optimized = null,
            /**
             * Indicates whether the climate preset is owned by the user or the system.
             */
            public string|null $owner = null,
        ) {}
    }
}

namespace Seam\Resources\Device\Properties\ThermostatDailyPrograms {
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
