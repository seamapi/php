<?php

namespace Seam\Resources;

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
         */
        public mixed $custom_metadata,
        /**
         * ID of the device.
         */
        public string|null $device_id,
        /**
         * Manufacturer of the device. Represents the hardware brand, which may differ from the provider.
         */
        public DeviceDeviceManufacturer|null $device_manufacturer,
        /**
         * Provider of the device. Represents the third-party service through which the device is controlled.
         */
        public DeviceDeviceProvider|null $device_provider,
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
        public bool|null $is_managed,
        /**
         * Location information for the device.
         */
        public DeviceLocation|null $location,
        /**
         * Optional nickname to describe the device, settable through Seam.
         */
        public string|null $nickname,
        /**
         * Properties of the device.
         */
        public DeviceProperties|null $properties,
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

/**
 * Latest accelerometer Z-axis reading for a Minut device.
 */
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
        /**
         * Time of latest accelerometer Z-axis reading for a Minut device.
         */
        public string|null $time,
        /**
         * Value of latest accelerometer Z-axis reading for a Minut device.
         */
        public float|null $value,
    ) {}
}

/**
 * Accessory keypad properties and state.
 */
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
        /**
         * Keypad battery properties.
         */
        public DeviceBattery|null $battery,
        /**
         * Indicates if an accessory keypad is connected to the device.
         */
        public bool|null $is_connected,
    ) {}
}

/**
 * Active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
 */
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
        public bool|null $is_override_allowed,
        /**
         * Number of minutes for which a person at the thermostat can change the thermostat's settings after the activation of the scheduled [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets). See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
         */
        public int|null $max_override_period_minutes,
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
 * Metadata for an Akiles device.
 */
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
        /**
         * Group ID to which to add users for an Akiles device.
         */
        public string|null $_member_group_id,
        /**
         * Gadget ID for an Akiles device.
         */
        public string|null $gadget_id,
        /**
         * Gadget name for an Akiles device.
         */
        public string|null $gadget_name,
        /**
         * Product name for an Akiles device.
         */
        public string|null $product_name,
    ) {}
}

/**
 * Appearance-related properties, as reported by the device.
 */
class DeviceAppearance
{
    public static function from_json(mixed $json): DeviceAppearance|null
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
 * Metadata for an Aqara device.
 */
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
        /**
         * Device name for an Aqara device.
         */
        public string|null $device_name,
        /**
         * Device ID (did) for an Aqara device.
         */
        public string|null $did,
        /**
         * Firmware version for an Aqara device.
         */
        public string|null $firmware_version,
        /**
         * Model identifier for an Aqara device.
         */
        public string|null $model,
        /**
         * Model type for an Aqara device.
         */
        public float|null $model_type,
        /**
         * Parent gateway device ID for an Aqara device.
         */
        public string|null $parent_did,
        /**
         * Position (room) ID for an Aqara device.
         */
        public string|null $position_id,
        /**
         * Time zone reported for an Aqara device (e.g. GMT-07:00).
         */
        public string|null $time_zone,
    ) {}
}

/**
 * ASSA ABLOY Credential Service metadata for the phone.
 */
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
        /**
         * Endpoints associated with the phone.
         */
        public array $endpoints,
        /**
         * Indicates whether the credential service has active endpoints associated with the phone.
         */
        public bool|null $has_active_endpoint,
    ) {}
}

/**
 * Metadata for an ASSA ABLOY Vostio system.
 */
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

    public function __construct(
        /**
         * Encoder name for an ASSA ABLOY Vostio system.
         */
        public string|null $encoder_name,
    ) {}
}

/**
 * Metadata for an August device.
 */
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
        /**
         * Indicates whether an August device has a keypad.
         */
        public bool|null $has_keypad,
        /**
         * House ID for an August device.
         */
        public string|null $house_id,
        /**
         * House name for an August device.
         */
        public string|null $house_name,
        /**
         * Keypad battery level for an August device.
         */
        public string|null $keypad_battery_level,
        /**
         * Lock ID for an August device.
         */
        public string|null $lock_id,
        /**
         * Lock name for an August device.
         */
        public string|null $lock_name,
        /**
         * Model for an August device.
         */
        public string|null $model,
    ) {}
}

/**
 * Available [climate presets](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for the thermostat.
 */
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
        public string|null $climate_preset_mode,
        /**
         * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_celsius,
        /**
         * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_fahrenheit,
        /**
         * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
        public string|null $display_name,
        /**
         * Metadata specific to the Ecobee climate, if applicable.
         */
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        /**
         * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
         */
        public string|null $fan_mode_setting,
        /**
         * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_celsius,
        /**
         * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_fahrenheit,
        /**
         * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
         */
        public string|null $hvac_mode_setting,
        /**
         * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
         *
         * @deprecated Use 'thermostat_schedule.is_override_allowed'
         */
        public bool|null $manual_override_allowed,
        /**
         * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
        public string|null $name,
    ) {}
}

/**
 * Metadata for an Avigilon Alta system.
 */
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
        /**
         * Entry name for an Avigilon Alta system.
         */
        public string|null $entry_name,
        /**
         * Total count of entry relays for an Avigilon Alta system.
         */
        public float|null $entry_relays_total_count,
        /**
         * Organization name for an Avigilon Alta system.
         */
        public string|null $org_name,
        /**
         * Site ID for an Avigilon Alta system.
         */
        public float|null $site_id,
        /**
         * Site name for an Avigilon Alta system.
         */
        public string|null $site_name,
        /**
         * Zone ID for an Avigilon Alta system.
         */
        public float|null $zone_id,
        /**
         * Zone name for an Avigilon Alta system.
         */
        public string|null $zone_name,
    ) {}
}

/**
 * Keypad battery properties.
 */
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

/**
 * Metadata for a Brivo device.
 */
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
        /**
         * Indicates whether the Brivo access point has activation (remote unlock) enabled.
         */
        public bool|null $activation_enabled,
        /**
         * Device name for a Brivo device.
         */
        public string|null $device_name,
    ) {}
}

/**
 * Constraints on access codes for the device. Seam represents each constraint as an object with a `constraint_type` property. Depending on the constraint type, there may also be additional properties. Note that some constraints are manufacturer- or device-specific.
 */
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
        /**
         * Maximum name length constraint for access codes.
         */
        public float|null $max_length,
        /**
         * Minimum name length constraint for access codes.
         */
        public float|null $min_length,
    ) {}
}

/**
 * Metadata for a ControlByWeb device.
 */
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
        /**
         * Device ID for a ControlByWeb device.
         */
        public string|null $device_id,
        /**
         * Device name for a ControlByWeb device.
         */
        public string|null $device_name,
        /**
         * Relay name for a ControlByWeb device.
         */
        public string|null $relay_name,
    ) {}
}

/**
 * Current climate setting.
 */
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
        public string|null $climate_preset_mode,
        /**
         * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_celsius,
        /**
         * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_fahrenheit,
        /**
         * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
        public string|null $display_name,
        /**
         * Metadata specific to the Ecobee climate, if applicable.
         */
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        /**
         * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
         */
        public string|null $fan_mode_setting,
        /**
         * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_celsius,
        /**
         * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_fahrenheit,
        /**
         * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
         */
        public string|null $hvac_mode_setting,
        /**
         * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
         *
         * @deprecated Use 'thermostat_schedule.is_override_allowed'
         */
        public bool|null $manual_override_allowed,
        /**
         * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
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
        public string|null $climate_preset_mode,
        /**
         * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_celsius,
        /**
         * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $cooling_set_point_fahrenheit,
        /**
         * Display name for the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
        public string|null $display_name,
        /**
         * Metadata specific to the Ecobee climate, if applicable.
         */
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        /**
         * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
         */
        public string|null $fan_mode_setting,
        /**
         * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_celsius,
        /**
         * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
         */
        public float|null $heating_set_point_fahrenheit,
        /**
         * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
         */
        public string|null $hvac_mode_setting,
        /**
         * Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
         *
         * @deprecated Use 'thermostat_schedule.is_override_allowed'
         */
        public bool|null $manual_override_allowed,
        /**
         * User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
         */
        public string|null $name,
    ) {}
}

/**
 * Manufacturer of the device. Represents the hardware brand, which may differ from the provider.
 */
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
        /**
         * Display name for the manufacturer, such as `August`, `Yale`, `Salto`, and so on.
         */
        public string|null $display_name,
        /**
         * Image URL for the manufacturer logo.
         */
        public string|null $image_url,
        /**
         * Manufacturer identifier, such as `august`, `yale`, `salto`, and so on.
         */
        public string|null $manufacturer,
    ) {}
}

/**
 * Provider of the device. Represents the third-party service through which the device is controlled.
 */
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
        public string|null $image_url,
        /**
         * Provider category. Indicates the third-party provider type, such as `stable`, for stable integrations, or `internal`, for internal integrations.
         */
        public string|null $provider_category,
    ) {}
}

/**
 * Metadata for a dormakaba Oracode device.
 */
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
        /**
         * Device ID for a dormakaba Oracode device.
         */
        public mixed $device_id,
        /**
         * Door ID for a dormakaba Oracode device.
         */
        public float|null $door_id,
        /**
         * Indicates whether a door is wireless for a dormakaba Oracode device.
         */
        public bool|null $door_is_wireless,
        /**
         * Door name for a dormakaba Oracode device.
         */
        public string|null $door_name,
        /**
         * IANA time zone for a dormakaba Oracode device.
         */
        public string|null $iana_timezone,
        /**
         * Predefined time slots for a dormakaba Oracode device.
         */
        public array $predefined_time_slots,
        /**
         * Site ID for a dormakaba Oracode device.
         *
         * @deprecated Previously marked as "@DEPRECATED."
         */
        public float|null $site_id,
        /**
         * Site name for a dormakaba Oracode device.
         */
        public string|null $site_name,
    ) {}
}

/**
 * Metadata for an ecobee device.
 */
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
        /**
         * Device name for an ecobee device.
         */
        public string|null $device_name,
        /**
         * Device ID for an ecobee device.
         */
        public string|null $ecobee_device_id,
    ) {}
}

/**
 * Endpoints associated with the phone.
 */
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
        /**
         * ID of the associated endpoint.
         */
        public string|null $endpoint_id,
        /**
         * Indicated whether the endpoint is active.
         */
        public bool|null $is_active,
    ) {}
}

/**
 * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
 */
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
        public bool|null $is_bridge_error,
        /**
         * Indicates that the error is a [connected account](https://docs.seam.co/api/connected_accounts) error.
         */
        public bool|null $is_connected_account_error,
        /**
         * Indicates that the error is not a device error.
         */
        public bool|null $is_device_error,
        /**
         * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
    ) {}
}

/**
 * Features for a TTLock device.
 */
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
        /**
         * Indicates whether a TTLock device supports auto-lock time configuration.
         */
        public bool|null $auto_lock_time_config,
        /**
         * Indicates whether a TTLock device supports an incomplete keyboard passcode.
         */
        public bool|null $incomplete_keyboard_passcode,
        /**
         * Indicates whether a TTLock device supports the lock command.
         */
        public bool|null $lock_command,
        /**
         * Indicates whether a TTLock device supports a passcode.
         */
        public bool|null $passcode,
        /**
         * Indicates whether a TTLock device supports passcode management.
         */
        public bool|null $passcode_management,
        /**
         * Indicates whether a TTLock device supports unlock via gateway.
         */
        public bool|null $unlock_via_gateway,
        /**
         * Indicates whether a TTLock device supports Wi-Fi.
         */
        public bool|null $wifi,
    ) {}
}

/**
 * Metadata for a 4SUITES device.
 */
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
        /**
         * Device ID for a 4SUITES device.
         */
        public float|null $device_id,
        /**
         * Device name for a 4SUITES device.
         */
        public string|null $device_name,
        /**
         * Reclose delay, in seconds, for a 4SUITES device.
         */
        public float|null $reclose_delay_in_seconds,
    ) {}
}

/**
 * Metadata for a Genie device.
 */
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
        /**
         * Lock name for a Genie device.
         */
        public string|null $device_name,
        /**
         * Door name for a Genie device.
         */
        public string|null $door_name,
    ) {}
}

/**
 * Metadata for a Honeywell Resideo device.
 */
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
        /**
         * Device name for a Honeywell Resideo device.
         */
        public string|null $device_name,
        /**
         * Device ID for a Honeywell Resideo device.
         */
        public string|null $honeywell_resideo_device_id,
    ) {}
}

/**
 * Latest humidity reading for a Minut device.
 */
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
        /**
         * Time of latest humidity reading for a Minut device.
         */
        public string|null $time,
        /**
         * Value of latest humidity reading for a Minut device.
         */
        public float|null $value,
    ) {}
}

/**
 * Metadata for an igloohome device.
 */
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
        /**
         * Bridge ID for an igloohome device.
         */
        public string|null $bridge_id,
        /**
         * Bridge name for an igloohome device.
         */
        public string|null $bridge_name,
        /**
         * Device ID for an igloohome device.
         */
        public string|null $device_id,
        /**
         * Device name for an igloohome device.
         */
        public string|null $device_name,
        /**
         * Indicates whether a keypad is linked to a bridge for an igloohome device.
         */
        public bool|null $is_accessory_keypad_linked_to_bridge,
        /**
         * Keypad ID for an igloohome device.
         */
        public string|null $keypad_id,
    ) {}
}

/**
 * Metadata for an igloo device.
 */
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
        /**
         * Bridge ID for an igloo device.
         */
        public string|null $bridge_id,
        /**
         * Device ID for an igloo device.
         */
        public string|null $device_id,
        /**
         * Model for an igloo device.
         */
        public string|null $model,
    ) {}
}

/**
 * Metadata for a KeyNest device.
 */
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
        /**
         * Address for a KeyNest device.
         */
        public string|null $address,
        /**
         * Current or last store ID for a KeyNest device.
         */
        public float|null $current_or_last_store_id,
        /**
         * Current status for a KeyNest device.
         */
        public string|null $current_status,
        /**
         * Current user company for a KeyNest device.
         */
        public string|null $current_user_company,
        /**
         * Current user email for a KeyNest device.
         */
        public string|null $current_user_email,
        /**
         * Current user name for a KeyNest device.
         */
        public string|null $current_user_name,
        /**
         * Current user phone number for a KeyNest device.
         */
        public string|null $current_user_phone_number,
        /**
         * Default office ID for a KeyNest device.
         */
        public float|null $default_office_id,
        /**
         * Device name for a KeyNest device.
         */
        public string|null $device_name,
        /**
         * Fob ID for a KeyNest device.
         */
        public float|null $fob_id,
        /**
         * Handover method for a KeyNest device.
         */
        public string|null $handover_method,
        /**
         * Whether the KeyNest device has a photo.
         */
        public bool|null $has_photo,
        /**
         * Whether the key is in a locker that does not support the access codes API.
         */
        public bool|null $is_quadient_locker,
        /**
         * Key ID for a KeyNest device.
         */
        public string|null $key_id,
        /**
         * Key notes for a KeyNest device.
         */
        public string|null $key_notes,
        /**
         * KeyNest app user for a KeyNest device.
         */
        public string|null $keynest_app_user,
        /**
         * Last movement timestamp for a KeyNest device.
         */
        public string|null $last_movement,
        /**
         * Property ID for a KeyNest device.
         */
        public string|null $property_id,
        /**
         * Property postcode for a KeyNest device.
         */
        public string|null $property_postcode,
        /**
         * Status type for a KeyNest device.
         */
        public string|null $status_type,
        /**
         * Subscription plan for a KeyNest device.
         */
        public string|null $subscription_plan,
    ) {}
}

/**
 * Keypad battery status.
 */
class DeviceKeypadBattery
{
    public static function from_json(mixed $json): DeviceKeypadBattery|null
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
 * Metadata for a Kisi device.
 */
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
        /**
         * Description for a Kisi device.
         */
        public string|null $description,
        /**
         * Lock ID for a Kisi device.
         */
        public float|null $lock_id,
        /**
         * Lock name for a Kisi device.
         */
        public string|null $lock_name,
        /**
         * Place name for a Kisi device.
         */
        public string|null $place_name,
    ) {}
}

/**
 * Metadata for a Korelock device.
 */
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
        /**
         * Device ID for a Korelock device.
         */
        public string|null $device_id,
        /**
         * Device name for a Korelock device.
         */
        public string|null $device_name,
        /**
         * Firmware version for a Korelock device.
         */
        public string|null $firmware_version,
        /**
         * Location ID for a Korelock device. Required for timebound access codes.
         */
        public string|null $location_id,
        /**
         * Model code for a Korelock device.
         */
        public string|null $model_code,
        /**
         * Serial number for a Korelock device.
         */
        public string|null $serial_number,
        /**
         * WiFi signal strength (0-1) for a Korelock device.
         */
        public float|null $wifi_signal_strength,
    ) {}
}

/**
 * Metadata for a Kwikset device.
 */
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
        /**
         * Device ID for a Kwikset device.
         */
        public string|null $device_id,
        /**
         * Device name for a Kwikset device.
         */
        public string|null $device_name,
        /**
         * Model number for a Kwikset device.
         */
        public string|null $model_number,
    ) {}
}

/**
 * Latest sensor values for a Minut device.
 */
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
        /**
         * Latest accelerometer Z-axis reading for a Minut device.
         */
        public DeviceAccelerometerZ|null $accelerometer_z,
        /**
         * Latest humidity reading for a Minut device.
         */
        public DeviceHumidity|null $humidity,
        /**
         * Latest pressure reading for a Minut device.
         */
        public DevicePressure|null $pressure,
        /**
         * Latest sound reading for a Minut device.
         */
        public DeviceSound|null $sound,
        /**
         * Latest temperature reading for a Minut device.
         */
        public DeviceTemperature|null $temperature,
    ) {}
}

/**
 * Location information for the device.
 */
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
        /**
         * Name of the device location.
         */
        public string|null $location_name,
        /**
         * Time zone of the device location.
         */
        public string|null $time_zone,
        /**
         * Time zone of the device location.
         *
         * @deprecated Use `time_zone` instead.
         */
        public string|null $timezone,
    ) {}
}

/**
 * Metadata for a Lockly device.
 */
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
        /**
         * Device ID for a Lockly device.
         */
        public string|null $device_id,
        /**
         * Device name for a Lockly device.
         */
        public string|null $device_name,
        /**
         * Model for a Lockly device.
         */
        public string|null $model,
    ) {}
}

/**
 * Metadata for a Minut device.
 */
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
        /**
         * Device ID for a Minut device.
         */
        public string|null $device_id,
        /**
         * Device name for a Minut device.
         */
        public string|null $device_name,
        /**
         * Latest sensor values for a Minut device.
         */
        public DeviceLatestSensorValues|null $latest_sensor_values,
    ) {}
}

/**
 * Device model-related properties.
 */
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
        /**
         * @deprecated use device.properties.model.can_connect_accessory_keypad
         */
        public bool|null $accessory_keypad_supported,
        /**
         * Indicates whether the device can connect a accessory keypad.
         */
        public bool|null $can_connect_accessory_keypad,
        /**
         * Display name of the device model.
         */
        public string|null $display_name,
        /**
         * Indicates whether the device has a built in accessory keypad.
         */
        public bool|null $has_built_in_keypad,
        /**
         * Display name that corresponds to the manufacturer-specific terminology for the device.
         */
        public string|null $manufacturer_display_name,
        /**
         * @deprecated use device.can_program_offline_access_codes.
         */
        public bool|null $offline_access_codes_supported,
        /**
         * @deprecated use device.can_program_online_access_codes.
         */
        public bool|null $online_access_codes_supported,
    ) {}
}

/**
 * Metadata for a Google Nest device.
 */
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
        /**
         * Custom device name for a Google Nest device. The device owner sets this value.
         */
        public string|null $device_custom_name,
        /**
         * Device name for a Google Nest device. Google sets this value.
         */
        public string|null $device_name,
        /**
         * Display name for a Google Nest device.
         */
        public string|null $display_name,
        /**
         * Device ID for a Google Nest device.
         */
        public string|null $nest_device_id,
    ) {}
}

/**
 * Metadata for a NoiseAware device.
 */
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
        /**
         * Device ID for a NoiseAware device.
         */
        public string|null $device_id,
        /**
         * Device model for a NoiseAware device.
         */
        public string|null $device_model,
        /**
         * Device name for a NoiseAware device.
         */
        public string|null $device_name,
        /**
         * Noise level, in decibels, for a NoiseAware device.
         */
        public float|null $noise_level_decibel,
        /**
         * Noise level, expressed as a Noise Risk Score (NRS), for a NoiseAware device.
         */
        public float|null $noise_level_nrs,
    ) {}
}

/**
 * Metadata for a Nuki device.
 */
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
        /**
         * Device ID for a Nuki device.
         */
        public string|null $device_id,
        /**
         * Device name for a Nuki device.
         */
        public string|null $device_name,
        /**
         * Indicates whether keypad 2 is paired for a Nuki device.
         */
        public bool|null $keypad_2_paired,
        /**
         * Indicates whether the keypad battery is in a critical state for a Nuki device.
         */
        public bool|null $keypad_battery_critical,
        /**
         * Indicates whether the keypad is paired for a Nuki device.
         */
        public bool|null $keypad_paired,
    ) {}
}

/**
 * Time frames that may be requested when creating an offline access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
 */
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
        /**
         * Label for this option. For a single-option device, the product name (for example, `algoPIN` or `SmartPIN`); for a multi-option device, a label that distinguishes it (for example, `Hourly` or `Fixed start times`).
         */
        public string|null $display_name,
        /**
         * iCalendar recurrence rule (RRULE) that the end date must fall on. Constrains which calendar dates are selectable, independent of the time-of-day rules.
         */
        public string|null $end_date_recurrence_rule,
        /**
         * When `true`, the start and end must fall at the same time of day (the caller picks which). Mutually exclusive with `time_pairs`.
         */
        public bool|null $matching_start_end_time,
        /**
         * Maximum duration this option covers, as an ISO 8601 duration (for example, `PT672H` or `P367D`). Omitted when there is no maximum.
         */
        public string|null $max_duration,
        /**
         * Minimum duration this option covers, as an ISO 8601 duration (for example, `PT1H` or `P29D`). Omitted when there is no minimum.
         */
        public string|null $min_duration,
        /**
         * iCalendar recurrence rule (RRULE) that the start date must fall on (for example, `FREQ=MONTHLY;BYDAY=1MO,3MO`). Constrains which calendar dates are selectable, independent of the time-of-day rules.
         */
        public string|null $start_date_recurrence_rule,
        /**
         * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
         */
        public array $time_pairs,
        /**
         * IANA time zone for interpreting `time_pairs` and the date recurrence rules. Present only when the option fixes times or dates.
         */
        public string|null $time_zone,
    ) {}
}

/**
 * Metadata for an Omnitec device.
 */
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
        /**
         * Whether the Omnitec lock has a connected gateway for remote operations.
         */
        public bool|null $has_gateway,
        /**
         * Operator-assigned alias for an Omnitec device.
         */
        public string|null $lock_alias,
        /**
         * Lock ID for an Omnitec device.
         */
        public float|null $lock_id,
        /**
         * Bluetooth MAC address for an Omnitec device.
         */
        public string|null $lock_mac,
        /**
         * Lock name for an Omnitec device.
         */
        public string|null $lock_name,
        /**
         * IANA time zone for the Omnitec device, used to schedule time-bound access codes at the correct local time (accounting for DST).
         */
        public string|null $time_zone,
        /**
         * Static UTC offset of the Omnitec lock in milliseconds. Does not account for DST.
         */
        public float|null $timezone_raw_offset_ms,
    ) {}
}

/**
 * Time frames that may be requested when creating an online access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
 */
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
        /**
         * Label for this option. For a single-option device, the product name (for example, `algoPIN` or `SmartPIN`); for a multi-option device, a label that distinguishes it (for example, `Hourly` or `Fixed start times`).
         */
        public string|null $display_name,
        /**
         * iCalendar recurrence rule (RRULE) that the end date must fall on. Constrains which calendar dates are selectable, independent of the time-of-day rules.
         */
        public string|null $end_date_recurrence_rule,
        /**
         * When `true`, the start and end must fall at the same time of day (the caller picks which). Mutually exclusive with `time_pairs`.
         */
        public bool|null $matching_start_end_time,
        /**
         * Maximum duration this option covers, as an ISO 8601 duration (for example, `PT672H` or `P367D`). Omitted when there is no maximum.
         */
        public string|null $max_duration,
        /**
         * Minimum duration this option covers, as an ISO 8601 duration (for example, `PT1H` or `P29D`). Omitted when there is no minimum.
         */
        public string|null $min_duration,
        /**
         * iCalendar recurrence rule (RRULE) that the start date must fall on (for example, `FREQ=MONTHLY;BYDAY=1MO,3MO`). Constrains which calendar dates are selectable, independent of the time-of-day rules.
         */
        public string|null $start_date_recurrence_rule,
        /**
         * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
         */
        public array $time_pairs,
        /**
         * IANA time zone for interpreting `time_pairs` and the date recurrence rules. Present only when the option fixes times or dates.
         */
        public string|null $time_zone,
    ) {}
}

/**
 * Array of thermostat daily program periods.
 */
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

/**
 * Predefined time slots for a dormakaba Oracode device.
 */
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
        /**
         * Check in time for a time slot for a dormakaba Oracode device.
         */
        public string|null $check_in_time,
        /**
         * Checkout time for a time slot for a dormakaba Oracode device.
         */
        public string|null $check_out_time,
        /**
         * ID of a user level for a dormakaba Oracode device.
         */
        public string|null $dormakaba_oracode_user_level_id,
        /**
         * Prefix for a user level for a dormakaba Oracode device.
         */
        public float|null $dormakaba_oracode_user_level_prefix,
        /**
         * Indicates whether a time slot for a dormakaba Oracode device is a 24-hour time slot.
         */
        public bool|null $is_24_hour,
        /**
         * Indicates whether a time slot for a dormakaba Oracode device is in biweekly mode.
         */
        public bool|null $is_biweekly_mode,
        /**
         * Indicates whether a time slot for a dormakaba Oracode device is a master time slot.
         */
        public bool|null $is_master,
        /**
         * Indicates whether a time slot for a dormakaba Oracode device is a one-shot time slot.
         */
        public bool|null $is_one_shot,
        /**
         * Name of a time slot for a dormakaba Oracode device.
         */
        public string|null $name,
        /**
         * Prefix for a time slot for a dormakaba Oracode device.
         */
        public float|null $prefix,
    ) {}
}

/**
 * Latest pressure reading for a Minut device.
 */
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
        /**
         * Time of latest pressure reading for a Minut device.
         */
        public string|null $time,
        /**
         * Value of latest pressure reading for a Minut device.
         */
        public float|null $value,
    ) {}
}

/**
 * Properties of the device.
 */
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
        /**
         * Accessory keypad properties and state.
         */
        public DeviceAccessoryKeypad|null $accessory_keypad,
        /**
         * Active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
         *
         * @deprecated Use `active_thermostat_schedule_id` with `/thermostats/schedules/get` instead.
         */
        public DeviceActiveThermostatSchedule|null $active_thermostat_schedule,
        /**
         * ID of the active [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
         */
        public string|null $active_thermostat_schedule_id,
        /**
         * Metadata for an Akiles device.
         */
        public DeviceAkilesMetadata|null $akiles_metadata,
        /**
         * Appearance-related properties, as reported by the device.
         */
        public DeviceAppearance|null $appearance,
        /**
         * Metadata for an Aqara device.
         */
        public DeviceAqaraMetadata|null $aqara_metadata,
        /**
         * ASSA ABLOY Credential Service metadata for the phone.
         */
        public DeviceAssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata,
        /**
         * Metadata for an ASSA ABLOY Vostio system.
         */
        public DeviceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        /**
         * Metadata for an August device.
         */
        public DeviceAugustMetadata|null $august_metadata,
        /**
         * The delay in seconds before the lock automatically locks after being unlocked.
         */
        public float|null $auto_lock_delay_seconds,
        /**
         * Indicates whether automatic locking is enabled.
         */
        public bool|null $auto_lock_enabled,
        /**
         * Climate preset modes that the thermostat supports, such as "home", "away", "wake", "sleep", "occupied", and "unoccupied".
         */
        public array|null $available_climate_preset_modes,
        /**
         * Available [climate presets](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for the thermostat.
         */
        public array $available_climate_presets,
        /**
         * Fan mode settings that the thermostat supports.
         */
        public array|null $available_fan_mode_settings,
        /**
         * HVAC mode settings that the thermostat supports.
         */
        public array|null $available_hvac_mode_settings,
        /**
         * Metadata for an Avigilon Alta system.
         */
        public DeviceAvigilonAltaMetadata|null $avigilon_alta_metadata,
        /**
         * Indicates whether the [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is currently enabled for the device. To disable it, set this to `false` using [/devices/update](https://docs.seam.co/api/devices/update).
         */
        public bool|null $backup_access_code_pool_enabled,
        /**
         * Represents the current status of the battery charge level.
         */
        public DeviceBattery|null $battery,
        /**
         * Indicates the battery level of the device as a decimal value between 0 and 1, inclusive.
         */
        public float|null $battery_level,
        /**
         * Metadata for a Brivo device.
         */
        public DeviceBrivoMetadata|null $brivo_metadata,
        /**
         * Constraints on access codes for the device. Seam represents each constraint as an object with a `constraint_type` property. Depending on the constraint type, there may also be additional properties. Note that some constraints are manufacturer- or device-specific.
         */
        public array $code_constraints,
        /**
         * Metadata for a ControlByWeb device.
         */
        public DeviceControlbywebMetadata|null $controlbyweb_metadata,
        /**
         * Current climate setting.
         */
        public DeviceCurrentClimateSetting|null $current_climate_setting,
        /**
         * Array of noise threshold IDs that are currently triggering.
         */
        public array|null $currently_triggering_noise_threshold_ids,
        /**
         * @deprecated use fallback_climate_preset_key to specify a fallback climate preset instead.
         */
        public DeviceDefaultClimateSetting|null $default_climate_setting,
        /**
         * Indicates whether the door is open.
         */
        public bool|null $door_open,
        /**
         * Metadata for a dormakaba Oracode device.
         */
        public DeviceDormakabaOracodeMetadata|null $dormakaba_oracode_metadata,
        /**
         * Metadata for an ecobee device.
         */
        public DeviceEcobeeMetadata|null $ecobee_metadata,
        /**
         * Key of the [fallback climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets/setting-the-fallback-climate-preset) for the thermostat.
         */
        public string|null $fallback_climate_preset_key,
        /**
         * @deprecated Use `current_climate_setting.fan_mode_setting` instead.
         */
        public string|null $fan_mode_setting,
        /**
         * Metadata for a 4SUITES device.
         */
        public DeviceFourSuitesMetadata|null $four_suites_metadata,
        /**
         * Metadata for a Genie device.
         */
        public DeviceGenieMetadata|null $genie_metadata,
        /**
         * Indicates whether the device has direct power.
         */
        public bool|null $has_direct_power,
        /**
         * Indicates whether the device supports native entry events.
         */
        public bool|null $has_native_entry_events,
        /**
         * Metadata for a Honeywell Resideo device.
         */
        public DeviceHoneywellResideoMetadata|null $honeywell_resideo_metadata,
        /**
         * Metadata for an igloo device.
         */
        public DeviceIglooMetadata|null $igloo_metadata,
        /**
         * Metadata for an igloohome device.
         */
        public DeviceIgloohomeMetadata|null $igloohome_metadata,
        /**
         * Alt text for the device image.
         */
        public string|null $image_alt_text,
        /**
         * Image URL for the device.
         */
        public string|null $image_url,
        /**
         * Indicates whether the connected HVAC system is currently cooling, as reported by the thermostat.
         */
        public bool|null $is_cooling,
        /**
         * Indicates whether the fan in the connected HVAC system is currently running, as reported by the thermostat.
         */
        public bool|null $is_fan_running,
        /**
         * Indicates whether the connected HVAC system is currently heating, as reported by the thermostat.
         */
        public bool|null $is_heating,
        /**
         * Indicates whether the current thermostat settings differ from the most recent active program or schedule that Seam activated. For this condition to occur, `current_climate_setting.manual_override_allowed` must also be `true`.
         */
        public bool|null $is_temporary_manual_override_active,
        /**
         * Metadata for a KeyNest device.
         */
        public DeviceKeynestMetadata|null $keynest_metadata,
        /**
         * Keypad battery status.
         */
        public DeviceKeypadBattery|null $keypad_battery,
        /**
         * Metadata for a Kisi device.
         */
        public DeviceKisiMetadata|null $kisi_metadata,
        /**
         * Metadata for a Korelock device.
         */
        public DeviceKorelockMetadata|null $korelock_metadata,
        /**
         * Metadata for a Kwikset device.
         */
        public DeviceKwiksetMetadata|null $kwikset_metadata,
        /**
         * Indicates whether the lock is locked.
         */
        public bool|null $locked,
        /**
         * Metadata for a Lockly device.
         */
        public DeviceLocklyMetadata|null $lockly_metadata,
        /**
         * Manufacturer of the device. When a device, such as a smart lock, is connected through a smart hub, the manufacturer of the device might be different from that of the smart hub.
         */
        public string|null $manufacturer,
        /**
         * Maximum number of active access codes that the device supports.
         */
        public float|null $max_active_codes_supported,
        /**
         * Maximum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °C.
         */
        public float|null $max_cooling_set_point_celsius,
        /**
         * Maximum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °F.
         */
        public float|null $max_cooling_set_point_fahrenheit,
        /**
         * Maximum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °C.
         */
        public float|null $max_heating_set_point_celsius,
        /**
         * Maximum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °F.
         */
        public float|null $max_heating_set_point_fahrenheit,
        /**
         * Maximum number of periods that the thermostat can support per day. For example, if the thermostat supports 4 periods per day, this value is 4.
         */
        public float|null $max_thermostat_daily_program_periods_per_day,
        /**
         * Maximum number of climate presets that the thermostat can support for weekly programming.
         */
        public float|null $max_unique_climate_presets_per_thermostat_weekly_program,
        /**
         * Minimum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °C.
         */
        public float|null $min_cooling_set_point_celsius,
        /**
         * Minimum [cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#cooling-set-point) in °F.
         */
        public float|null $min_cooling_set_point_fahrenheit,
        /**
         * Minimum [temperature difference](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#minimum-heating-cooling-temperature-delta) in °C between the cooling and heating set points when in heat-cool (auto) mode.
         */
        public float|null $min_heating_cooling_delta_celsius,
        /**
         * Minimum [temperature difference](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#minimum-heating-cooling-temperature-delta) in °F between the cooling and heating set points when in heat-cool (auto) mode.
         */
        public float|null $min_heating_cooling_delta_fahrenheit,
        /**
         * Minimum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °C.
         */
        public float|null $min_heating_set_point_celsius,
        /**
         * Minimum [heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points#heating-set-point) in °F.
         */
        public float|null $min_heating_set_point_fahrenheit,
        /**
         * Metadata for a Minut device.
         */
        public DeviceMinutMetadata|null $minut_metadata,
        /**
         * Device model-related properties.
         */
        public DeviceModel|null $model,
        /**
         * Name of the device.
         *
         * @deprecated use device.display_name instead
         */
        public string|null $name,
        /**
         * Metadata for a Google Nest device.
         */
        public DeviceNestMetadata|null $nest_metadata,
        /**
         * Indicates current noise level in decibels, if the device supports noise detection.
         */
        public float|null $noise_level_decibels,
        /**
         * Metadata for a NoiseAware device.
         */
        public DeviceNoiseawareMetadata|null $noiseaware_metadata,
        /**
         * Metadata for a Nuki device.
         */
        public DeviceNukiMetadata|null $nuki_metadata,
        /**
         * Indicates whether it is currently possible to use offline access codes for the device.
         *
         * @deprecated use device.can_program_offline_access_codes
         */
        public bool|null $offline_access_codes_enabled,
        /**
         * Time frames that may be requested when creating an offline access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
         */
        public array $offline_time_frame_options,
        /**
         * Metadata for an Omnitec device.
         */
        public DeviceOmnitecMetadata|null $omnitec_metadata,
        /**
         * Indicates whether the device is online.
         */
        public bool|null $online,
        /**
         * Indicates whether it is currently possible to use online access codes for the device.
         *
         * @deprecated use device.can_program_online_access_codes
         */
        public bool|null $online_access_codes_enabled,
        /**
         * Time frames that may be requested when creating an online access code, expressed as a list of options. The caller picks one option (by matching the requested duration when the options' duration ranges do not overlap, or by `display_name` when they do) and satisfies that one option's rules. When `undefined`, any time frame works.
         */
        public array $online_time_frame_options,
        /**
         * Reported relative humidity, as a value between 0 and 1, inclusive.
         */
        public float|null $relative_humidity,
        /**
         * Metadata for a Ring device.
         */
        public DeviceRingMetadata|null $ring_metadata,
        /**
         * Metadata for a Salto KS device.
         */
        public DeviceSaltoKsMetadata|null $salto_ks_metadata,
        /**
         * Metada for a Salto device.
         *
         * @deprecated Use `salto_ks_metadata ` instead.
         */
        public DeviceSaltoMetadata|null $salto_metadata,
        /**
         * Salto Space credential service metadata for the phone.
         */
        public DeviceSaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata,
        /**
         * Metadata for a Schlage device.
         */
        public DeviceSchlageMetadata|null $schlage_metadata,
        /**
         * Metadata for Seam Bridge.
         */
        public DeviceSeamBridgeMetadata|null $seam_bridge_metadata,
        /**
         * Metadata for a Sensi device.
         */
        public DeviceSensiMetadata|null $sensi_metadata,
        /**
         * Serial number of the device.
         */
        public string|null $serial_number,
        /**
         * Metadata for a SmartThings device.
         */
        public DeviceSmartthingsMetadata|null $smartthings_metadata,
        /**
         * Supported code lengths for access codes.
         */
        public array|null $supported_code_lengths,
        /**
         * @deprecated use device.properties.model.can_connect_accessory_keypad
         */
        public bool|null $supports_accessory_keypad,
        /**
         * Indicates whether the device supports a [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes).
         */
        public bool|null $supports_backup_access_code_pool,
        /**
         * @deprecated use offline_access_codes_enabled
         */
        public bool|null $supports_offline_access_codes,
        /**
         * Metadata for a tado° device.
         */
        public DeviceTadoMetadata|null $tado_metadata,
        /**
         * Metadata for a Tedee device.
         */
        public DeviceTedeeMetadata|null $tedee_metadata,
        /**
         * Reported temperature in °C.
         */
        public float|null $temperature_celsius,
        /**
         * Reported temperature in °F.
         */
        public float|null $temperature_fahrenheit,
        /**
         * Current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
         */
        public DeviceTemperatureThreshold|null $temperature_threshold,
        /**
         * Precision of the thermostat's period in minutes. For example, if the thermostat supports 15-minute periods, this value is 15. All values are relative to the top of the hour, so for 15 minutes, the periods would be 0, 15, 30, and 45 minutes past the hour.
         */
        public float|null $thermostat_daily_program_period_precision_minutes,
        /**
         * Configured [daily programs](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
         */
        public array $thermostat_daily_programs,
        /**
         * Current [weekly program](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-programs) for the thermostat.
         */
        public DeviceThermostatWeeklyProgram|null $thermostat_weekly_program,
        /**
         * Metadata for a TTLock device.
         */
        public DeviceTtlockMetadata|null $ttlock_metadata,
        /**
         * Metadata for a 2N device.
         */
        public DeviceTwoNMetadata|null $two_n_metadata,
        /**
         * Metadata for an Ultraloq device.
         */
        public DeviceUltraloqMetadata|null $ultraloq_metadata,
        /**
         * Metadata for an ASSA ABLOY Visionline system.
         */
        public DeviceVisionlineMetadata|null $visionline_metadata,
        /**
         * Metadata for a Wyze device.
         */
        public DeviceWyzeMetadata|null $wyze_metadata,
    ) {}
}

/**
 * Metadata for a Ring device.
 */
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
        /**
         * Device ID for a Ring device.
         */
        public string|null $device_id,
        /**
         * Device name for a Ring device.
         */
        public string|null $device_name,
    ) {}
}

/**
 * Metadata for a Salto KS device.
 */
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
        /**
         * Battery level for a Salto KS device.
         */
        public string|null $battery_level,
        /**
         * Customer reference for a Salto KS device.
         */
        public string|null $customer_reference,
        /**
         * Indicates whether the site has a Salto KS subscription that supports custom PINs.
         */
        public bool|null $has_custom_pin_subscription,
        /**
         * Lock ID for a Salto KS device.
         */
        public string|null $lock_id,
        /**
         * Lock type for a Salto KS device.
         */
        public string|null $lock_type,
        /**
         * Locked state for a Salto KS device.
         */
        public string|null $locked_state,
        /**
         * Model for a Salto KS device.
         */
        public string|null $model,
        /**
         * Site ID for the Salto KS site to which the device belongs.
         */
        public string|null $site_id,
        /**
         * Site name for the Salto KS site to which the device belongs.
         */
        public string|null $site_name,
    ) {}
}

/**
 * Metada for a Salto device.
 */
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
        /**
         * Battery level for a Salto device.
         */
        public string|null $battery_level,
        /**
         * Customer reference for a Salto device.
         */
        public string|null $customer_reference,
        /**
         * Lock ID for a Salto device.
         */
        public string|null $lock_id,
        /**
         * Lock type for a Salto device.
         */
        public string|null $lock_type,
        /**
         * Locked state for a Salto device.
         */
        public string|null $locked_state,
        /**
         * Model for a Salto device.
         */
        public string|null $model,
        /**
         * Site ID for the Salto KS site to which the device belongs.
         */
        public string|null $site_id,
        /**
         * Site name for the Salto KS site to which the device belongs.
         */
        public string|null $site_name,
    ) {}
}

/**
 * Salto Space credential service metadata for the phone.
 */
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

    public function __construct(
        /**
         * Indicates whether the credential service has an active associated phone.
         */
        public bool|null $has_active_phone,
    ) {}
}

/**
 * Metadata for a Schlage device.
 */
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
        /**
         * Device ID for a Schlage device.
         */
        public string|null $device_id,
        /**
         * Device name for a Schlage device.
         */
        public string|null $device_name,
        /**
         * Model for a Schlage device.
         */
        public string|null $model,
    ) {}
}

/**
 * Metadata for Seam Bridge.
 */
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
        /**
         * Device number for Seam Bridge.
         */
        public float|null $device_num,
        /**
         * Name for Seam Bridge.
         */
        public string|null $name,
        /**
         * Unlock method for Seam Bridge.
         */
        public string|null $unlock_method,
    ) {}
}

/**
 * Metadata for a Sensi device.
 */
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
        /**
         * Device ID for a Sensi device.
         */
        public string|null $device_id,
        /**
         * Device name for a Sensi device.
         */
        public string|null $device_name,
        /**
         * Set to true when the device does not support the /dual-setpoints API endpoint.
         */
        public bool|null $dual_setpoints_not_supported,
        /**
         * Product type for a Sensi device.
         */
        public string|null $product_type,
    ) {}
}

/**
 * Metadata for a SmartThings device.
 */
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
        /**
         * Device ID for a SmartThings device.
         */
        public string|null $device_id,
        /**
         * Device name for a SmartThings device.
         */
        public string|null $device_name,
        /**
         * Location ID for a SmartThings device.
         */
        public string|null $location_id,
        /**
         * Model for a SmartThings device.
         */
        public string|null $model,
    ) {}
}

/**
 * Latest sound reading for a Minut device.
 */
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
        /**
         * Time of latest sound reading for a Minut device.
         */
        public string|null $time,
        /**
         * Value of latest sound reading for a Minut device.
         */
        public float|null $value,
    ) {}
}

/**
 * Metadata for a tado° device.
 */
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
        /**
         * Device type for a tado° device.
         */
        public string|null $device_type,
        /**
         * Serial number for a tado° device.
         */
        public string|null $serial_no,
    ) {}
}

/**
 * Metadata for a Tedee device.
 */
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
        /**
         * Bridge ID for a Tedee device.
         */
        public float|null $bridge_id,
        /**
         * Bridge name for a Tedee device.
         */
        public string|null $bridge_name,
        /**
         * Device ID for a Tedee device.
         */
        public float|null $device_id,
        /**
         * Device model for a Tedee device.
         */
        public string|null $device_model,
        /**
         * Device name for a Tedee device.
         */
        public string|null $device_name,
        /**
         * Keypad ID for a Tedee device.
         */
        public float|null $keypad_id,
        /**
         * Serial number for a Tedee device.
         */
        public string|null $serial_number,
    ) {}
}

/**
 * Latest temperature reading for a Minut device.
 */
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
        /**
         * Time of latest temperature reading for a Minut device.
         */
        public string|null $time,
        /**
         * Value of latest temperature reading for a Minut device.
         */
        public float|null $value,
    ) {}
}

/**
 * Current [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) set for the thermostat.
 */
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

/**
 * Fixed start/end time pairings the caller chooses from. Mutually exclusive with `matching_start_end_time`.
 */
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

/**
 * Metadata for a TTLock device.
 */
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
        /**
         * Feature value for a TTLock device.
         */
        public string|null $feature_value,
        /**
         * Features for a TTLock device.
         */
        public DeviceFeatures|null $features,
        /**
         * Indicates whether a TTLock device has a gateway.
         */
        public bool|null $has_gateway,
        /**
         * Lock alias for a TTLock device.
         */
        public string|null $lock_alias,
        /**
         * Lock ID for a TTLock device.
         */
        public float|null $lock_id,
        /**
         * Lock-side timezone offset in milliseconds east of UTC, as configured in the TTLock app. Source of truth for the lock's wall-clock interpretation of access code start/end times — a misconfigured value here is the typical cause of customer "codes offset by N hours" reports. Diagnostic only; Seam does not convert times based on this value.
         */
        public float|null $timezone_raw_offset_ms,
        /**
         * Wireless keypads for a TTLock device.
         */
        public array $wireless_keypads,
    ) {}
}

/**
 * Metadata for a 2N device.
 */
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
        /**
         * Device ID for a 2N device.
         */
        public float|null $device_id,
        /**
         * Device name for a 2N device.
         */
        public string|null $device_name,
    ) {}
}

/**
 * Metadata for an Ultraloq device.
 */
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
        /**
         * Device ID for an Ultraloq device.
         */
        public string|null $device_id,
        /**
         * Device name for an Ultraloq device.
         */
        public string|null $device_name,
        /**
         * Device type for an Ultraloq device.
         */
        public string|null $device_type,
        /**
         * IANA timezone for the Ultraloq device.
         */
        public string|null $time_zone,
    ) {}
}

/**
 * Metadata for an ASSA ABLOY Visionline system.
 */
class DeviceVisionlineMetadata
{
    public static function from_json(mixed $json): DeviceVisionlineMetadata|null
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
        public string|null $encoder_id,
    ) {}
}

/**
 * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
 */
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

/**
 * Wireless keypads for a TTLock device.
 */
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
        /**
         * ID for a wireless keypad for a TTLock device.
         */
        public float|null $wireless_keypad_id,
        /**
         * Name for a wireless keypad for a TTLock device.
         */
        public string|null $wireless_keypad_name,
    ) {}
}

/**
 * Metadata for a Wyze device.
 */
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
        /**
         * Device ID for a Wyze device.
         */
        public string|null $device_id,
        /**
         * Device information model for a Wyze device.
         */
        public string|null $device_info_model,
        /**
         * Device name for a Wyze device.
         */
        public string|null $device_name,
        /**
         * Keypad UUID for a Wyze device.
         */
        public string|null $keypad_uuid,
        /**
         * Locker status (hardlock) for a Wyze device.
         */
        public float|null $locker_status_hardlock,
        /**
         * Product model for a Wyze device.
         */
        public string|null $product_model,
        /**
         * Product name for a Wyze device.
         */
        public string|null $product_name,
        /**
         * Product type for a Wyze device.
         */
        public string|null $product_type,
    ) {}
}
