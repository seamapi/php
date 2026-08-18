<?php

namespace Seam\Resources {
    /**
     * Represents an [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices). An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     */
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
                    fn($e) => UnmanagedDevice\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                location: isset($json->location)
                    ? UnmanagedDevice\Location::from_json($json->location)
                    : null,
                properties: isset($json->properties)
                    ? UnmanagedDevice\Properties::from_json($json->properties)
                    : null,
                warnings: array_map(
                    fn($w) => UnmanagedDevice\Warnings::from_json($w),
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
             * Type of the device.
             */
            public string|null $device_type,
            /**
             * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
             */
            public array $errors,
            /**
             * Indicates that Seam does not manage the device.
             */
            public false|null $is_managed,
            /**
             * Location information for the device.
             */
            public UnmanagedDevice\Location|null $location = null,
            /**
             * properties of the device.
             */
            public UnmanagedDevice\Properties|null $properties,
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

namespace Seam\Resources\UnmanagedDevice {
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
     * properties of the device.
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
                battery: isset($json->battery)
                    ? Properties\Battery::from_json($json->battery)
                    : null,
                battery_level: $json->battery_level ?? null,
                image_alt_text: $json->image_alt_text ?? null,
                image_url: $json->image_url ?? null,
                manufacturer: $json->manufacturer ?? null,
                model: isset($json->model)
                    ? Properties\Model::from_json($json->model)
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
            /**
             * Accessory keypad properties and state.
             */
            public Properties\AccessoryKeypad|null $accessory_keypad = null,
            /**
             * Represents the current status of the battery charge level.
             */
            public Properties\Battery|null $battery = null,
            /**
             * Indicates the battery level of the device as a decimal value between 0 and 1, inclusive.
             */
            public float|null $battery_level = null,
            /**
             * Alt text for the device image.
             */
            public string|null $image_alt_text = null,
            /**
             * Image URL for the device.
             */
            public string|null $image_url = null,
            /**
             * Manufacturer of the device. When a device, such as a smart lock, is connected through a smart hub, the manufacturer of the device might be different from that of the smart hub.
             */
            public string|null $manufacturer = null,
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
             * Indicates whether it is currently possible to use offline access codes for the device.
             *
             * @deprecated use device.can_program_offline_access_codes
             */
            public bool|null $offline_access_codes_enabled = null,
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

namespace Seam\Resources\UnmanagedDevice\Properties {
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
}

namespace Seam\Resources\UnmanagedDevice\Properties\AccessoryKeypad {
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
