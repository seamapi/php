<?php

namespace Seam\Resources;

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
                ? DeviceDeviceManufacturer::from_json(
                    $json->device_manufacturer,
                )
                : null,
            device_provider: isset($json->device_provider)
                ? DeviceDeviceProvider::from_json($json->device_provider)
                : null,
            device_type: $json->device_type ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => DeviceErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_managed: $json->is_managed ?? null,
            location: isset($json->location)
                ? DeviceLocation::from_json($json->location)
                : null,
            nickname: $json->nickname ?? null,
            properties: isset($json->properties)
                ? DeviceProperties::from_json($json->properties)
                : null,
            space_ids: $json->space_ids ?? null,
            warnings: array_map(
                fn($w) => DeviceWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public bool|null $can_configure_auto_lock,
        public bool|null $can_hvac_cool,
        public bool|null $can_hvac_heat,
        public bool|null $can_hvac_heat_cool,
        public bool|null $can_program_offline_access_codes,
        public bool|null $can_program_online_access_codes,
        public bool|null $can_program_thermostat_programs_as_different_each_day,
        public bool|null $can_program_thermostat_programs_as_same_each_day,
        public bool|null $can_program_thermostat_programs_as_weekday_weekend,
        public bool|null $can_remotely_lock,
        public bool|null $can_remotely_unlock,
        public bool|null $can_run_thermostat_programs,
        public bool|null $can_simulate_connection,
        public bool|null $can_simulate_disconnection,
        public bool|null $can_simulate_hub_connection,
        public bool|null $can_simulate_hub_disconnection,
        public bool|null $can_simulate_paid_subscription,
        public bool|null $can_simulate_removal,
        public bool|null $can_turn_off_hvac,
        public bool|null $can_unlock_with_code,
        public array|null $capabilities_supported,
        public string|null $connected_account_id,
        public string|null $created_at,
        public mixed $custom_metadata,
        public string|null $device_id,
        public DeviceDeviceManufacturer|null $device_manufacturer,
        public DeviceDeviceProvider|null $device_provider,
        public string|null $device_type,
        public string|null $display_name,
        public array $errors,
        public bool|null $is_managed,
        public DeviceLocation|null $location,
        public string|null $nickname,
        public DeviceProperties|null $properties,
        public array|null $space_ids,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class DeviceAccelerometerZ
{
    public static function from_json(mixed $json): DeviceAccelerometerZ|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}

class DeviceAccessoryKeypad
{
    public static function from_json(mixed $json): DeviceAccessoryKeypad|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            battery: isset($json->battery)
                ? DeviceBattery::from_json($json->battery)
                : null,
            is_connected: $json->is_connected ?? null,
        );
    }

    public function __construct(
        public DeviceBattery|null $battery,
        public bool|null $is_connected,
    ) {}
}

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

class DeviceAkilesMetadata
{
    public static function from_json(mixed $json): DeviceAkilesMetadata|null
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
        public string|null $_member_group_id,
        public string|null $gadget_id,
        public string|null $gadget_name,
        public string|null $product_name,
    ) {}
}

class DeviceAppearance
{
    public static function from_json(mixed $json): DeviceAppearance|null
    {
        if (!$json) {
            return null;
        }
        return new self(name: $json->name ?? null);
    }

    public function __construct(public string|null $name) {}
}

class DeviceAqaraMetadata
{
    public static function from_json(mixed $json): DeviceAqaraMetadata|null
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
        public string|null $device_name,
        public string|null $did,
        public string|null $firmware_version,
        public string|null $model,
        public float|null $model_type,
        public string|null $parent_did,
        public string|null $position_id,
        public string|null $time_zone,
    ) {}
}

class DeviceAssaAbloyCredentialServiceMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceAssaAbloyCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            endpoints: array_map(
                fn($e) => DeviceEndpoints::from_json($e),
                $json->endpoints ?? [],
            ),
            has_active_endpoint: $json->has_active_endpoint ?? null,
        );
    }

    public function __construct(
        public array $endpoints,
        public bool|null $has_active_endpoint,
    ) {}
}

class DeviceAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceAssaAbloyVostioMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(encoder_name: $json->encoder_name ?? null);
    }

    public function __construct(public string|null $encoder_name) {}
}

class DeviceAugustMetadata
{
    public static function from_json(mixed $json): DeviceAugustMetadata|null
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
        public bool|null $has_keypad,
        public string|null $house_id,
        public string|null $house_name,
        public string|null $keypad_battery_level,
        public string|null $lock_id,
        public string|null $lock_name,
        public string|null $model,
    ) {}
}

class DeviceAvailableClimatePresets
{
    public static function from_json(
        mixed $json,
    ): DeviceAvailableClimatePresets|null {
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
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            display_name: $json->display_name ?? null,
            ecobee_metadata: isset($json->ecobee_metadata)
                ? DeviceEcobeeMetadata::from_json($json->ecobee_metadata)
                : null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            manual_override_allowed: $json->manual_override_allowed ?? null,
            name: $json->name ?? null,
        );
    }

    public function __construct(
        public bool|null $can_delete,
        public bool|null $can_edit,
        public bool|null $can_use_with_thermostat_daily_programs,
        public string|null $climate_preset_key,
        public string|null $climate_preset_mode,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $display_name,
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $manual_override_allowed,
        public string|null $name,
    ) {}
}

class DeviceAvigilonAltaMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceAvigilonAltaMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            entry_name: $json->entry_name ?? null,
            entry_relays_total_count: $json->entry_relays_total_count ?? null,
            org_name: $json->org_name ?? null,
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
            zone_id: $json->zone_id ?? null,
            zone_name: $json->zone_name ?? null,
        );
    }

    public function __construct(
        public string|null $entry_name,
        public float|null $entry_relays_total_count,
        public string|null $org_name,
        public float|null $site_id,
        public string|null $site_name,
        public float|null $zone_id,
        public string|null $zone_name,
    ) {}
}

class DeviceBattery
{
    public static function from_json(mixed $json): DeviceBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level ?? null);
    }

    public function __construct(public float|null $level) {}
}

class DeviceBrivoMetadata
{
    public static function from_json(mixed $json): DeviceBrivoMetadata|null
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
        public bool|null $activation_enabled,
        public string|null $device_name,
    ) {}
}

class DeviceCodeConstraints
{
    public static function from_json(mixed $json): DeviceCodeConstraints|null
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
        public float|null $max_length,
        public float|null $min_length,
    ) {}
}

class DeviceControlbywebMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceControlbywebMetadata|null {
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $relay_name,
    ) {}
}

class DeviceCurrentClimateSetting
{
    public static function from_json(
        mixed $json,
    ): DeviceCurrentClimateSetting|null {
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
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            display_name: $json->display_name ?? null,
            ecobee_metadata: isset($json->ecobee_metadata)
                ? DeviceEcobeeMetadata::from_json($json->ecobee_metadata)
                : null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            manual_override_allowed: $json->manual_override_allowed ?? null,
            name: $json->name ?? null,
        );
    }

    public function __construct(
        public bool|null $can_delete,
        public bool|null $can_edit,
        public bool|null $can_use_with_thermostat_daily_programs,
        public string|null $climate_preset_key,
        public string|null $climate_preset_mode,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $display_name,
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $manual_override_allowed,
        public string|null $name,
    ) {}
}

class DeviceDefaultClimateSetting
{
    public static function from_json(
        mixed $json,
    ): DeviceDefaultClimateSetting|null {
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
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            display_name: $json->display_name ?? null,
            ecobee_metadata: isset($json->ecobee_metadata)
                ? DeviceEcobeeMetadata::from_json($json->ecobee_metadata)
                : null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            manual_override_allowed: $json->manual_override_allowed ?? null,
            name: $json->name ?? null,
        );
    }

    public function __construct(
        public bool|null $can_delete,
        public bool|null $can_edit,
        public bool|null $can_use_with_thermostat_daily_programs,
        public string|null $climate_preset_key,
        public string|null $climate_preset_mode,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $display_name,
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $manual_override_allowed,
        public string|null $name,
    ) {}
}

class DeviceDeviceManufacturer
{
    public static function from_json(mixed $json): DeviceDeviceManufacturer|null
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
        public string|null $display_name,
        public string|null $image_url,
        public string|null $manufacturer,
    ) {}
}

class DeviceDeviceProvider
{
    public static function from_json(mixed $json): DeviceDeviceProvider|null
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
        public string|null $device_provider_name,
        public string|null $display_name,
        public string|null $image_url,
        public string|null $provider_category,
    ) {}
}

class DeviceDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceDormakabaOracodeMetadata|null {
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
                fn($p) => DevicePredefinedTimeSlots::from_json($p),
                $json->predefined_time_slots ?? [],
            ),
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
        );
    }

    public function __construct(
        public mixed $device_id,
        public float|null $door_id,
        public bool|null $door_is_wireless,
        public string|null $door_name,
        public string|null $iana_timezone,
        public array $predefined_time_slots,
        public float|null $site_id,
        public string|null $site_name,
    ) {}
}

class DeviceEcobeeMetadata
{
    public static function from_json(mixed $json): DeviceEcobeeMetadata|null
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
        public string|null $device_name,
        public string|null $ecobee_device_id,
    ) {}
}

class DeviceEndpoints
{
    public static function from_json(mixed $json): DeviceEndpoints|null
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
        public string|null $endpoint_id,
        public bool|null $is_active,
    ) {}
}

class DeviceErrors
{
    public static function from_json(mixed $json): DeviceErrors|null
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
        public string|null $created_at,
        public string|null $error_code,
        public bool|null $is_bridge_error,
        public bool|null $is_connected_account_error,
        public bool|null $is_device_error,
        public string|null $message,
    ) {}
}

class DeviceFeatures
{
    public static function from_json(mixed $json): DeviceFeatures|null
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
        public bool|null $auto_lock_time_config,
        public bool|null $incomplete_keyboard_passcode,
        public bool|null $lock_command,
        public bool|null $passcode,
        public bool|null $passcode_management,
        public bool|null $unlock_via_gateway,
        public bool|null $wifi,
    ) {}
}

class DeviceFourSuitesMetadata
{
    public static function from_json(mixed $json): DeviceFourSuitesMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            reclose_delay_in_seconds: $json->reclose_delay_in_seconds ?? null,
        );
    }

    public function __construct(
        public float|null $device_id,
        public string|null $device_name,
        public float|null $reclose_delay_in_seconds,
    ) {}
}

class DeviceGenieMetadata
{
    public static function from_json(mixed $json): DeviceGenieMetadata|null
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
        public string|null $device_name,
        public string|null $door_name,
    ) {}
}

class DeviceHoneywellResideoMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceHoneywellResideoMetadata|null {
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
        public string|null $device_name,
        public string|null $honeywell_resideo_device_id,
    ) {}
}

class DeviceHumidity
{
    public static function from_json(mixed $json): DeviceHumidity|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}

class DeviceIgloohomeMetadata
{
    public static function from_json(mixed $json): DeviceIgloohomeMetadata|null
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
        public string|null $bridge_id,
        public string|null $bridge_name,
        public string|null $device_id,
        public string|null $device_name,
        public bool|null $is_accessory_keypad_linked_to_bridge,
        public string|null $keypad_id,
    ) {}
}

class DeviceIglooMetadata
{
    public static function from_json(mixed $json): DeviceIglooMetadata|null
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
        public string|null $bridge_id,
        public string|null $device_id,
        public string|null $model,
    ) {}
}

class DeviceKeynestMetadata
{
    public static function from_json(mixed $json): DeviceKeynestMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            address: $json->address ?? null,
            current_or_last_store_id: $json->current_or_last_store_id ?? null,
            current_status: $json->current_status ?? null,
            current_user_company: $json->current_user_company ?? null,
            current_user_email: $json->current_user_email ?? null,
            current_user_name: $json->current_user_name ?? null,
            current_user_phone_number: $json->current_user_phone_number ?? null,
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
        public string|null $address,
        public float|null $current_or_last_store_id,
        public string|null $current_status,
        public string|null $current_user_company,
        public string|null $current_user_email,
        public string|null $current_user_name,
        public string|null $current_user_phone_number,
        public float|null $default_office_id,
        public string|null $device_name,
        public float|null $fob_id,
        public string|null $handover_method,
        public bool|null $has_photo,
        public bool|null $is_quadient_locker,
        public string|null $key_id,
        public string|null $key_notes,
        public string|null $keynest_app_user,
        public string|null $last_movement,
        public string|null $property_id,
        public string|null $property_postcode,
        public string|null $status_type,
        public string|null $subscription_plan,
    ) {}
}

class DeviceKeypadBattery
{
    public static function from_json(mixed $json): DeviceKeypadBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level ?? null);
    }

    public function __construct(public float|null $level) {}
}

class DeviceKisiMetadata
{
    public static function from_json(mixed $json): DeviceKisiMetadata|null
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
        public string|null $description,
        public float|null $lock_id,
        public string|null $lock_name,
        public string|null $place_name,
    ) {}
}

class DeviceKorelockMetadata
{
    public static function from_json(mixed $json): DeviceKorelockMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $firmware_version,
        public string|null $location_id,
        public string|null $model_code,
        public string|null $serial_number,
        public float|null $wifi_signal_strength,
    ) {}
}

class DeviceKwiksetMetadata
{
    public static function from_json(mixed $json): DeviceKwiksetMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $model_number,
    ) {}
}

class DeviceLatestSensorValues
{
    public static function from_json(mixed $json): DeviceLatestSensorValues|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            accelerometer_z: isset($json->accelerometer_z)
                ? DeviceAccelerometerZ::from_json($json->accelerometer_z)
                : null,
            humidity: isset($json->humidity)
                ? DeviceHumidity::from_json($json->humidity)
                : null,
            pressure: isset($json->pressure)
                ? DevicePressure::from_json($json->pressure)
                : null,
            sound: isset($json->sound)
                ? DeviceSound::from_json($json->sound)
                : null,
            temperature: isset($json->temperature)
                ? DeviceTemperature::from_json($json->temperature)
                : null,
        );
    }

    public function __construct(
        public DeviceAccelerometerZ|null $accelerometer_z,
        public DeviceHumidity|null $humidity,
        public DevicePressure|null $pressure,
        public DeviceSound|null $sound,
        public DeviceTemperature|null $temperature,
    ) {}
}

class DeviceLocation
{
    public static function from_json(mixed $json): DeviceLocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            location_name: $json->location_name ?? null,
            time_zone: $json->time_zone ?? null,
            timezone: $json->timezone ?? null,
        );
    }

    public function __construct(
        public string|null $location_name,
        public string|null $time_zone,
        public string|null $timezone,
    ) {}
}

class DeviceLocklyMetadata
{
    public static function from_json(mixed $json): DeviceLocklyMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $model,
    ) {}
}

class DeviceMinutMetadata
{
    public static function from_json(mixed $json): DeviceMinutMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            latest_sensor_values: isset($json->latest_sensor_values)
                ? DeviceLatestSensorValues::from_json(
                    $json->latest_sensor_values,
                )
                : null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public DeviceLatestSensorValues|null $latest_sensor_values,
    ) {}
}

class DeviceModel
{
    public static function from_json(mixed $json): DeviceModel|null
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
            manufacturer_display_name: $json->manufacturer_display_name ?? null,
            offline_access_codes_supported: $json->offline_access_codes_supported ??
                null,
            online_access_codes_supported: $json->online_access_codes_supported ??
                null,
        );
    }

    public function __construct(
        public bool|null $accessory_keypad_supported,
        public bool|null $can_connect_accessory_keypad,
        public string|null $display_name,
        public bool|null $has_built_in_keypad,
        public string|null $manufacturer_display_name,
        public bool|null $offline_access_codes_supported,
        public bool|null $online_access_codes_supported,
    ) {}
}

class DeviceNestMetadata
{
    public static function from_json(mixed $json): DeviceNestMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_custom_name: $json->device_custom_name ?? null,
            device_name: $json->device_name ?? null,
            display_name: $json->display_name ?? null,
            nest_device_id: $json->nest_device_id ?? null,
        );
    }

    public function __construct(
        public string|null $device_custom_name,
        public string|null $device_name,
        public string|null $display_name,
        public string|null $nest_device_id,
    ) {}
}

class DeviceNoiseawareMetadata
{
    public static function from_json(mixed $json): DeviceNoiseawareMetadata|null
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
        public string|null $device_id,
        public string|null $device_model,
        public string|null $device_name,
        public float|null $noise_level_decibel,
        public float|null $noise_level_nrs,
    ) {}
}

class DeviceNukiMetadata
{
    public static function from_json(mixed $json): DeviceNukiMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public bool|null $keypad_2_paired,
        public bool|null $keypad_battery_critical,
        public bool|null $keypad_paired,
    ) {}
}

class DeviceOfflineTimeFrameOptions
{
    public static function from_json(
        mixed $json,
    ): DeviceOfflineTimeFrameOptions|null {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name ?? null,
            end_date_recurrence_rule: $json->end_date_recurrence_rule ?? null,
            matching_start_end_time: $json->matching_start_end_time ?? null,
            max_duration: $json->max_duration ?? null,
            min_duration: $json->min_duration ?? null,
            start_date_recurrence_rule: $json->start_date_recurrence_rule ??
                null,
            time_pairs: array_map(
                fn($t) => DeviceTimePairs::from_json($t),
                $json->time_pairs ?? [],
            ),
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $display_name,
        public string|null $end_date_recurrence_rule,
        public bool|null $matching_start_end_time,
        public string|null $max_duration,
        public string|null $min_duration,
        public string|null $start_date_recurrence_rule,
        public array $time_pairs,
        public string|null $time_zone,
    ) {}
}

class DeviceOmnitecMetadata
{
    public static function from_json(mixed $json): DeviceOmnitecMetadata|null
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
        public bool|null $has_gateway,
        public string|null $lock_alias,
        public float|null $lock_id,
        public string|null $lock_mac,
        public string|null $lock_name,
        public string|null $time_zone,
        public float|null $timezone_raw_offset_ms,
    ) {}
}

class DeviceOnlineTimeFrameOptions
{
    public static function from_json(
        mixed $json,
    ): DeviceOnlineTimeFrameOptions|null {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name ?? null,
            end_date_recurrence_rule: $json->end_date_recurrence_rule ?? null,
            matching_start_end_time: $json->matching_start_end_time ?? null,
            max_duration: $json->max_duration ?? null,
            min_duration: $json->min_duration ?? null,
            start_date_recurrence_rule: $json->start_date_recurrence_rule ??
                null,
            time_pairs: array_map(
                fn($t) => DeviceTimePairs::from_json($t),
                $json->time_pairs ?? [],
            ),
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $display_name,
        public string|null $end_date_recurrence_rule,
        public bool|null $matching_start_end_time,
        public string|null $max_duration,
        public string|null $min_duration,
        public string|null $start_date_recurrence_rule,
        public array $time_pairs,
        public string|null $time_zone,
    ) {}
}

class DevicePeriods
{
    public static function from_json(mixed $json): DevicePeriods|null
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
        public string|null $climate_preset_key,
        public string|null $starts_at_time,
    ) {}
}

class DevicePredefinedTimeSlots
{
    public static function from_json(
        mixed $json,
    ): DevicePredefinedTimeSlots|null {
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
        public string|null $check_in_time,
        public string|null $check_out_time,
        public string|null $dormakaba_oracode_user_level_id,
        public float|null $dormakaba_oracode_user_level_prefix,
        public bool|null $is_24_hour,
        public bool|null $is_biweekly_mode,
        public bool|null $is_master,
        public bool|null $is_one_shot,
        public string|null $name,
        public float|null $prefix,
    ) {}
}

class DevicePressure
{
    public static function from_json(mixed $json): DevicePressure|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}

class DeviceProperties
{
    public static function from_json(mixed $json): DeviceProperties|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            accessory_keypad: isset($json->accessory_keypad)
                ? DeviceAccessoryKeypad::from_json($json->accessory_keypad)
                : null,
            active_thermostat_schedule: isset($json->active_thermostat_schedule)
                ? DeviceActiveThermostatSchedule::from_json(
                    $json->active_thermostat_schedule,
                )
                : null,
            active_thermostat_schedule_id: $json->active_thermostat_schedule_id ??
                null,
            akiles_metadata: isset($json->akiles_metadata)
                ? DeviceAkilesMetadata::from_json($json->akiles_metadata)
                : null,
            appearance: isset($json->appearance)
                ? DeviceAppearance::from_json($json->appearance)
                : null,
            aqara_metadata: isset($json->aqara_metadata)
                ? DeviceAqaraMetadata::from_json($json->aqara_metadata)
                : null,
            assa_abloy_credential_service_metadata: isset(
                $json->assa_abloy_credential_service_metadata,
            )
                ? DeviceAssaAbloyCredentialServiceMetadata::from_json(
                    $json->assa_abloy_credential_service_metadata,
                )
                : null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? DeviceAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            august_metadata: isset($json->august_metadata)
                ? DeviceAugustMetadata::from_json($json->august_metadata)
                : null,
            auto_lock_delay_seconds: $json->auto_lock_delay_seconds ?? null,
            auto_lock_enabled: $json->auto_lock_enabled ?? null,
            available_climate_preset_modes: $json->available_climate_preset_modes ??
                null,
            available_climate_presets: array_map(
                fn($a) => DeviceAvailableClimatePresets::from_json($a),
                $json->available_climate_presets ?? [],
            ),
            available_fan_mode_settings: $json->available_fan_mode_settings ??
                null,
            available_hvac_mode_settings: $json->available_hvac_mode_settings ??
                null,
            avigilon_alta_metadata: isset($json->avigilon_alta_metadata)
                ? DeviceAvigilonAltaMetadata::from_json(
                    $json->avigilon_alta_metadata,
                )
                : null,
            backup_access_code_pool_enabled: $json->backup_access_code_pool_enabled ??
                null,
            battery: isset($json->battery)
                ? DeviceBattery::from_json($json->battery)
                : null,
            battery_level: $json->battery_level ?? null,
            brivo_metadata: isset($json->brivo_metadata)
                ? DeviceBrivoMetadata::from_json($json->brivo_metadata)
                : null,
            code_constraints: array_map(
                fn($c) => DeviceCodeConstraints::from_json($c),
                $json->code_constraints ?? [],
            ),
            controlbyweb_metadata: isset($json->controlbyweb_metadata)
                ? DeviceControlbywebMetadata::from_json(
                    $json->controlbyweb_metadata,
                )
                : null,
            current_climate_setting: isset($json->current_climate_setting)
                ? DeviceCurrentClimateSetting::from_json(
                    $json->current_climate_setting,
                )
                : null,
            currently_triggering_noise_threshold_ids: $json->currently_triggering_noise_threshold_ids ??
                null,
            default_climate_setting: isset($json->default_climate_setting)
                ? DeviceDefaultClimateSetting::from_json(
                    $json->default_climate_setting,
                )
                : null,
            door_open: $json->door_open ?? null,
            dormakaba_oracode_metadata: isset($json->dormakaba_oracode_metadata)
                ? DeviceDormakabaOracodeMetadata::from_json(
                    $json->dormakaba_oracode_metadata,
                )
                : null,
            ecobee_metadata: isset($json->ecobee_metadata)
                ? DeviceEcobeeMetadata::from_json($json->ecobee_metadata)
                : null,
            fallback_climate_preset_key: $json->fallback_climate_preset_key ??
                null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            four_suites_metadata: isset($json->four_suites_metadata)
                ? DeviceFourSuitesMetadata::from_json(
                    $json->four_suites_metadata,
                )
                : null,
            genie_metadata: isset($json->genie_metadata)
                ? DeviceGenieMetadata::from_json($json->genie_metadata)
                : null,
            has_direct_power: $json->has_direct_power ?? null,
            has_native_entry_events: $json->has_native_entry_events ?? null,
            honeywell_resideo_metadata: isset($json->honeywell_resideo_metadata)
                ? DeviceHoneywellResideoMetadata::from_json(
                    $json->honeywell_resideo_metadata,
                )
                : null,
            igloo_metadata: isset($json->igloo_metadata)
                ? DeviceIglooMetadata::from_json($json->igloo_metadata)
                : null,
            igloohome_metadata: isset($json->igloohome_metadata)
                ? DeviceIgloohomeMetadata::from_json($json->igloohome_metadata)
                : null,
            image_alt_text: $json->image_alt_text ?? null,
            image_url: $json->image_url ?? null,
            is_cooling: $json->is_cooling ?? null,
            is_fan_running: $json->is_fan_running ?? null,
            is_heating: $json->is_heating ?? null,
            is_temporary_manual_override_active: $json->is_temporary_manual_override_active ??
                null,
            keynest_metadata: isset($json->keynest_metadata)
                ? DeviceKeynestMetadata::from_json($json->keynest_metadata)
                : null,
            keypad_battery: isset($json->keypad_battery)
                ? DeviceKeypadBattery::from_json($json->keypad_battery)
                : null,
            kisi_metadata: isset($json->kisi_metadata)
                ? DeviceKisiMetadata::from_json($json->kisi_metadata)
                : null,
            korelock_metadata: isset($json->korelock_metadata)
                ? DeviceKorelockMetadata::from_json($json->korelock_metadata)
                : null,
            kwikset_metadata: isset($json->kwikset_metadata)
                ? DeviceKwiksetMetadata::from_json($json->kwikset_metadata)
                : null,
            locked: $json->locked ?? null,
            lockly_metadata: isset($json->lockly_metadata)
                ? DeviceLocklyMetadata::from_json($json->lockly_metadata)
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
                ? DeviceMinutMetadata::from_json($json->minut_metadata)
                : null,
            model: isset($json->model)
                ? DeviceModel::from_json($json->model)
                : null,
            name: $json->name ?? null,
            nest_metadata: isset($json->nest_metadata)
                ? DeviceNestMetadata::from_json($json->nest_metadata)
                : null,
            noise_level_decibels: $json->noise_level_decibels ?? null,
            noiseaware_metadata: isset($json->noiseaware_metadata)
                ? DeviceNoiseawareMetadata::from_json(
                    $json->noiseaware_metadata,
                )
                : null,
            nuki_metadata: isset($json->nuki_metadata)
                ? DeviceNukiMetadata::from_json($json->nuki_metadata)
                : null,
            offline_access_codes_enabled: $json->offline_access_codes_enabled ??
                null,
            offline_time_frame_options: array_map(
                fn($o) => DeviceOfflineTimeFrameOptions::from_json($o),
                $json->offline_time_frame_options ?? [],
            ),
            omnitec_metadata: isset($json->omnitec_metadata)
                ? DeviceOmnitecMetadata::from_json($json->omnitec_metadata)
                : null,
            online: $json->online ?? null,
            online_access_codes_enabled: $json->online_access_codes_enabled ??
                null,
            online_time_frame_options: array_map(
                fn($o) => DeviceOnlineTimeFrameOptions::from_json($o),
                $json->online_time_frame_options ?? [],
            ),
            relative_humidity: $json->relative_humidity ?? null,
            ring_metadata: isset($json->ring_metadata)
                ? DeviceRingMetadata::from_json($json->ring_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? DeviceSaltoKsMetadata::from_json($json->salto_ks_metadata)
                : null,
            salto_metadata: isset($json->salto_metadata)
                ? DeviceSaltoMetadata::from_json($json->salto_metadata)
                : null,
            salto_space_credential_service_metadata: isset(
                $json->salto_space_credential_service_metadata,
            )
                ? DeviceSaltoSpaceCredentialServiceMetadata::from_json(
                    $json->salto_space_credential_service_metadata,
                )
                : null,
            schlage_metadata: isset($json->schlage_metadata)
                ? DeviceSchlageMetadata::from_json($json->schlage_metadata)
                : null,
            seam_bridge_metadata: isset($json->seam_bridge_metadata)
                ? DeviceSeamBridgeMetadata::from_json(
                    $json->seam_bridge_metadata,
                )
                : null,
            sensi_metadata: isset($json->sensi_metadata)
                ? DeviceSensiMetadata::from_json($json->sensi_metadata)
                : null,
            serial_number: $json->serial_number ?? null,
            smartthings_metadata: isset($json->smartthings_metadata)
                ? DeviceSmartthingsMetadata::from_json(
                    $json->smartthings_metadata,
                )
                : null,
            supported_code_lengths: $json->supported_code_lengths ?? null,
            supports_accessory_keypad: $json->supports_accessory_keypad ?? null,
            supports_backup_access_code_pool: $json->supports_backup_access_code_pool ??
                null,
            supports_offline_access_codes: $json->supports_offline_access_codes ??
                null,
            tado_metadata: isset($json->tado_metadata)
                ? DeviceTadoMetadata::from_json($json->tado_metadata)
                : null,
            tedee_metadata: isset($json->tedee_metadata)
                ? DeviceTedeeMetadata::from_json($json->tedee_metadata)
                : null,
            temperature_celsius: $json->temperature_celsius ?? null,
            temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
            temperature_threshold: isset($json->temperature_threshold)
                ? DeviceTemperatureThreshold::from_json(
                    $json->temperature_threshold,
                )
                : null,
            thermostat_daily_program_period_precision_minutes: $json->thermostat_daily_program_period_precision_minutes ??
                null,
            thermostat_daily_programs: array_map(
                fn($t) => DeviceThermostatDailyPrograms::from_json($t),
                $json->thermostat_daily_programs ?? [],
            ),
            thermostat_weekly_program: isset($json->thermostat_weekly_program)
                ? DeviceThermostatWeeklyProgram::from_json(
                    $json->thermostat_weekly_program,
                )
                : null,
            ttlock_metadata: isset($json->ttlock_metadata)
                ? DeviceTtlockMetadata::from_json($json->ttlock_metadata)
                : null,
            two_n_metadata: isset($json->two_n_metadata)
                ? DeviceTwoNMetadata::from_json($json->two_n_metadata)
                : null,
            ultraloq_metadata: isset($json->ultraloq_metadata)
                ? DeviceUltraloqMetadata::from_json($json->ultraloq_metadata)
                : null,
            visionline_metadata: isset($json->visionline_metadata)
                ? DeviceVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            wyze_metadata: isset($json->wyze_metadata)
                ? DeviceWyzeMetadata::from_json($json->wyze_metadata)
                : null,
        );
    }

    public function __construct(
        public DeviceAccessoryKeypad|null $accessory_keypad,
        public DeviceActiveThermostatSchedule|null $active_thermostat_schedule,
        public string|null $active_thermostat_schedule_id,
        public DeviceAkilesMetadata|null $akiles_metadata,
        public DeviceAppearance|null $appearance,
        public DeviceAqaraMetadata|null $aqara_metadata,
        public DeviceAssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata,
        public DeviceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public DeviceAugustMetadata|null $august_metadata,
        public float|null $auto_lock_delay_seconds,
        public bool|null $auto_lock_enabled,
        public array|null $available_climate_preset_modes,
        public array $available_climate_presets,
        public array|null $available_fan_mode_settings,
        public array|null $available_hvac_mode_settings,
        public DeviceAvigilonAltaMetadata|null $avigilon_alta_metadata,
        public bool|null $backup_access_code_pool_enabled,
        public DeviceBattery|null $battery,
        public float|null $battery_level,
        public DeviceBrivoMetadata|null $brivo_metadata,
        public array $code_constraints,
        public DeviceControlbywebMetadata|null $controlbyweb_metadata,
        public DeviceCurrentClimateSetting|null $current_climate_setting,
        public array|null $currently_triggering_noise_threshold_ids,
        public DeviceDefaultClimateSetting|null $default_climate_setting,
        public bool|null $door_open,
        public DeviceDormakabaOracodeMetadata|null $dormakaba_oracode_metadata,
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        public string|null $fallback_climate_preset_key,
        public string|null $fan_mode_setting,
        public DeviceFourSuitesMetadata|null $four_suites_metadata,
        public DeviceGenieMetadata|null $genie_metadata,
        public bool|null $has_direct_power,
        public bool|null $has_native_entry_events,
        public DeviceHoneywellResideoMetadata|null $honeywell_resideo_metadata,
        public DeviceIglooMetadata|null $igloo_metadata,
        public DeviceIgloohomeMetadata|null $igloohome_metadata,
        public string|null $image_alt_text,
        public string|null $image_url,
        public bool|null $is_cooling,
        public bool|null $is_fan_running,
        public bool|null $is_heating,
        public bool|null $is_temporary_manual_override_active,
        public DeviceKeynestMetadata|null $keynest_metadata,
        public DeviceKeypadBattery|null $keypad_battery,
        public DeviceKisiMetadata|null $kisi_metadata,
        public DeviceKorelockMetadata|null $korelock_metadata,
        public DeviceKwiksetMetadata|null $kwikset_metadata,
        public bool|null $locked,
        public DeviceLocklyMetadata|null $lockly_metadata,
        public string|null $manufacturer,
        public float|null $max_active_codes_supported,
        public float|null $max_cooling_set_point_celsius,
        public float|null $max_cooling_set_point_fahrenheit,
        public float|null $max_heating_set_point_celsius,
        public float|null $max_heating_set_point_fahrenheit,
        public float|null $max_thermostat_daily_program_periods_per_day,
        public float|null $max_unique_climate_presets_per_thermostat_weekly_program,
        public float|null $min_cooling_set_point_celsius,
        public float|null $min_cooling_set_point_fahrenheit,
        public float|null $min_heating_cooling_delta_celsius,
        public float|null $min_heating_cooling_delta_fahrenheit,
        public float|null $min_heating_set_point_celsius,
        public float|null $min_heating_set_point_fahrenheit,
        public DeviceMinutMetadata|null $minut_metadata,
        public DeviceModel|null $model,
        public string|null $name,
        public DeviceNestMetadata|null $nest_metadata,
        public float|null $noise_level_decibels,
        public DeviceNoiseawareMetadata|null $noiseaware_metadata,
        public DeviceNukiMetadata|null $nuki_metadata,
        public bool|null $offline_access_codes_enabled,
        public array $offline_time_frame_options,
        public DeviceOmnitecMetadata|null $omnitec_metadata,
        public bool|null $online,
        public bool|null $online_access_codes_enabled,
        public array $online_time_frame_options,
        public float|null $relative_humidity,
        public DeviceRingMetadata|null $ring_metadata,
        public DeviceSaltoKsMetadata|null $salto_ks_metadata,
        public DeviceSaltoMetadata|null $salto_metadata,
        public DeviceSaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata,
        public DeviceSchlageMetadata|null $schlage_metadata,
        public DeviceSeamBridgeMetadata|null $seam_bridge_metadata,
        public DeviceSensiMetadata|null $sensi_metadata,
        public string|null $serial_number,
        public DeviceSmartthingsMetadata|null $smartthings_metadata,
        public array|null $supported_code_lengths,
        public bool|null $supports_accessory_keypad,
        public bool|null $supports_backup_access_code_pool,
        public bool|null $supports_offline_access_codes,
        public DeviceTadoMetadata|null $tado_metadata,
        public DeviceTedeeMetadata|null $tedee_metadata,
        public float|null $temperature_celsius,
        public float|null $temperature_fahrenheit,
        public DeviceTemperatureThreshold|null $temperature_threshold,
        public float|null $thermostat_daily_program_period_precision_minutes,
        public array $thermostat_daily_programs,
        public DeviceThermostatWeeklyProgram|null $thermostat_weekly_program,
        public DeviceTtlockMetadata|null $ttlock_metadata,
        public DeviceTwoNMetadata|null $two_n_metadata,
        public DeviceUltraloqMetadata|null $ultraloq_metadata,
        public DeviceVisionlineMetadata|null $visionline_metadata,
        public DeviceWyzeMetadata|null $wyze_metadata,
    ) {}
}

class DeviceRingMetadata
{
    public static function from_json(mixed $json): DeviceRingMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
    ) {}
}

class DeviceSaltoKsMetadata
{
    public static function from_json(mixed $json): DeviceSaltoKsMetadata|null
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
        public string|null $battery_level,
        public string|null $customer_reference,
        public bool|null $has_custom_pin_subscription,
        public string|null $lock_id,
        public string|null $lock_type,
        public string|null $locked_state,
        public string|null $model,
        public string|null $site_id,
        public string|null $site_name,
    ) {}
}

class DeviceSaltoMetadata
{
    public static function from_json(mixed $json): DeviceSaltoMetadata|null
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
        public string|null $battery_level,
        public string|null $customer_reference,
        public string|null $lock_id,
        public string|null $lock_type,
        public string|null $locked_state,
        public string|null $model,
        public string|null $site_id,
        public string|null $site_name,
    ) {}
}

class DeviceSaltoSpaceCredentialServiceMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceSaltoSpaceCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(has_active_phone: $json->has_active_phone ?? null);
    }

    public function __construct(public bool|null $has_active_phone) {}
}

class DeviceSchlageMetadata
{
    public static function from_json(mixed $json): DeviceSchlageMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $model,
    ) {}
}

class DeviceSeamBridgeMetadata
{
    public static function from_json(mixed $json): DeviceSeamBridgeMetadata|null
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
        public float|null $device_num,
        public string|null $name,
        public string|null $unlock_method,
    ) {}
}

class DeviceSensiMetadata
{
    public static function from_json(mixed $json): DeviceSensiMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            dual_setpoints_not_supported: $json->dual_setpoints_not_supported ??
                null,
            product_type: $json->product_type ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public bool|null $dual_setpoints_not_supported,
        public string|null $product_type,
    ) {}
}

class DeviceSmartthingsMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceSmartthingsMetadata|null {
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $location_id,
        public string|null $model,
    ) {}
}

class DeviceSound
{
    public static function from_json(mixed $json): DeviceSound|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}

class DeviceTadoMetadata
{
    public static function from_json(mixed $json): DeviceTadoMetadata|null
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
        public string|null $device_type,
        public string|null $serial_no,
    ) {}
}

class DeviceTedeeMetadata
{
    public static function from_json(mixed $json): DeviceTedeeMetadata|null
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
        public float|null $bridge_id,
        public string|null $bridge_name,
        public float|null $device_id,
        public string|null $device_model,
        public string|null $device_name,
        public float|null $keypad_id,
        public string|null $serial_number,
    ) {}
}

class DeviceTemperature
{
    public static function from_json(mixed $json): DeviceTemperature|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}

class DeviceTemperatureThreshold
{
    public static function from_json(
        mixed $json,
    ): DeviceTemperatureThreshold|null {
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
        public float|null $lower_limit_celsius,
        public float|null $lower_limit_fahrenheit,
        public float|null $upper_limit_celsius,
        public float|null $upper_limit_fahrenheit,
    ) {}
}

class DeviceThermostatDailyPrograms
{
    public static function from_json(
        mixed $json,
    ): DeviceThermostatDailyPrograms|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            periods: array_map(
                fn($p) => DevicePeriods::from_json($p),
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

class DeviceThermostatWeeklyProgram
{
    public static function from_json(
        mixed $json,
    ): DeviceThermostatWeeklyProgram|null {
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
        public string|null $created_at,
        public string|null $friday_program_id,
        public string|null $monday_program_id,
        public string|null $saturday_program_id,
        public string|null $sunday_program_id,
        public string|null $thursday_program_id,
        public string|null $tuesday_program_id,
        public string|null $wednesday_program_id,
    ) {}
}

class DeviceTimePairs
{
    public static function from_json(mixed $json): DeviceTimePairs|null
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
        public string|null $display_name,
        public string|null $end_time,
        public string|null $start_time,
    ) {}
}

class DeviceTtlockMetadata
{
    public static function from_json(mixed $json): DeviceTtlockMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            feature_value: $json->feature_value ?? null,
            features: isset($json->features)
                ? DeviceFeatures::from_json($json->features)
                : null,
            has_gateway: $json->has_gateway ?? null,
            lock_alias: $json->lock_alias ?? null,
            lock_id: $json->lock_id ?? null,
            timezone_raw_offset_ms: $json->timezone_raw_offset_ms ?? null,
            wireless_keypads: array_map(
                fn($w) => DeviceWirelessKeypads::from_json($w),
                $json->wireless_keypads ?? [],
            ),
        );
    }

    public function __construct(
        public string|null $feature_value,
        public DeviceFeatures|null $features,
        public bool|null $has_gateway,
        public string|null $lock_alias,
        public float|null $lock_id,
        public float|null $timezone_raw_offset_ms,
        public array $wireless_keypads,
    ) {}
}

class DeviceTwoNMetadata
{
    public static function from_json(mixed $json): DeviceTwoNMetadata|null
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
        public float|null $device_id,
        public string|null $device_name,
    ) {}
}

class DeviceUltraloqMetadata
{
    public static function from_json(mixed $json): DeviceUltraloqMetadata|null
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
        public string|null $device_id,
        public string|null $device_name,
        public string|null $device_type,
        public string|null $time_zone,
    ) {}
}

class DeviceVisionlineMetadata
{
    public static function from_json(mixed $json): DeviceVisionlineMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(encoder_id: $json->encoder_id ?? null);
    }

    public function __construct(public string|null $encoder_id) {}
}

class DeviceWarnings
{
    public static function from_json(mixed $json): DeviceWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            active_access_code_count: $json->active_access_code_count ?? null,
            created_at: $json->created_at ?? null,
            max_active_access_code_count: $json->max_active_access_code_count ??
                null,
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public int|null $active_access_code_count,
        public string|null $created_at,
        public int|null $max_active_access_code_count,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}

class DeviceWirelessKeypads
{
    public static function from_json(mixed $json): DeviceWirelessKeypads|null
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
        public float|null $wireless_keypad_id,
        public string|null $wireless_keypad_name,
    ) {}
}

class DeviceWyzeMetadata
{
    public static function from_json(mixed $json): DeviceWyzeMetadata|null
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
        public string|null $device_id,
        public string|null $device_info_model,
        public string|null $device_name,
        public string|null $keypad_uuid,
        public float|null $locker_status_hardlock,
        public string|null $product_model,
        public string|null $product_name,
        public string|null $product_type,
    ) {}
}
