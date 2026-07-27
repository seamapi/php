<?php

namespace Seam\Resources;

class UnmanagedDevice
{
    public static function from_json(mixed $json): UnmanagedDevice|null
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
            device_type: $json->device_type ?? null,
            errors: array_map(
                fn($e) => UnmanagedDeviceErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_managed: $json->is_managed ?? null,
            location: isset($json->location)
                ? UnmanagedDeviceLocation::from_json($json->location)
                : null,
            properties: isset($json->properties)
                ? UnmanagedDeviceProperties::from_json($json->properties)
                : null,
            warnings: array_map(
                fn($w) => UnmanagedDeviceWarnings::from_json($w),
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
        public string|null $device_type,
        public array $errors,
        public bool|null $is_managed,
        public UnmanagedDeviceLocation|null $location,
        public UnmanagedDeviceProperties|null $properties,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class UnmanagedDeviceAccessoryKeypad
{
    public static function from_json(
        mixed $json,
    ): UnmanagedDeviceAccessoryKeypad|null {
        if (!$json) {
            return null;
        }
        return new self(
            battery: isset($json->battery)
                ? UnmanagedDeviceBattery::from_json($json->battery)
                : null,
            is_connected: $json->is_connected ?? null,
        );
    }

    public function __construct(
        public UnmanagedDeviceBattery|null $battery,
        public bool|null $is_connected,
    ) {}
}

class UnmanagedDeviceBattery
{
    public static function from_json(mixed $json): UnmanagedDeviceBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level ?? null);
    }

    public function __construct(public float|null $level) {}
}

class UnmanagedDeviceErrors
{
    public static function from_json(mixed $json): UnmanagedDeviceErrors|null
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

class UnmanagedDeviceLocation
{
    public static function from_json(mixed $json): UnmanagedDeviceLocation|null
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

class UnmanagedDeviceModel
{
    public static function from_json(mixed $json): UnmanagedDeviceModel|null
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

class UnmanagedDeviceProperties
{
    public static function from_json(
        mixed $json,
    ): UnmanagedDeviceProperties|null {
        if (!$json) {
            return null;
        }
        return new self(
            accessory_keypad: isset($json->accessory_keypad)
                ? UnmanagedDeviceAccessoryKeypad::from_json(
                    $json->accessory_keypad,
                )
                : null,
            battery: isset($json->battery)
                ? UnmanagedDeviceBattery::from_json($json->battery)
                : null,
            battery_level: $json->battery_level ?? null,
            image_alt_text: $json->image_alt_text ?? null,
            image_url: $json->image_url ?? null,
            manufacturer: $json->manufacturer ?? null,
            model: isset($json->model)
                ? UnmanagedDeviceModel::from_json($json->model)
                : null,
            name: $json->name ?? null,
            offline_access_codes_enabled: $json->offline_access_codes_enabled ??
                null,
            online: $json->online ?? null,
            online_access_codes_enabled: $json->online_access_codes_enabled ??
                null,
        );
    }

    public function __construct(
        public UnmanagedDeviceAccessoryKeypad|null $accessory_keypad,
        public UnmanagedDeviceBattery|null $battery,
        public float|null $battery_level,
        public string|null $image_alt_text,
        public string|null $image_url,
        public string|null $manufacturer,
        public UnmanagedDeviceModel|null $model,
        public string|null $name,
        public bool|null $offline_access_codes_enabled,
        public bool|null $online,
        public bool|null $online_access_codes_enabled,
    ) {}
}

class UnmanagedDeviceWarnings
{
    public static function from_json(mixed $json): UnmanagedDeviceWarnings|null
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
