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
                capabilities_supported: $json->capabilities_supported ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                custom_metadata: $json->custom_metadata ?? null,
                device_id: $json->device_id ?? null,
                device_type: is_string($json->device_type ?? null)
                    ? \Seam\Resources\UnmanagedDevice\DeviceType::tryFrom(
                            $json->device_type,
                        ) ?? $json->device_type
                    : null,
                errors: array_map(
                    fn($e) => \Seam\Resources\UnmanagedDevice\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                properties: isset($json->properties)
                    ? \Seam\Resources\UnmanagedDevice\Properties::from_json(
                        $json->properties,
                    )
                    : null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\UnmanagedDevice\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
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
                location: isset($json->location)
                    ? \Seam\Resources\UnmanagedDevice\Location::from_json(
                        $json->location,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Collection of capabilities that the device supports when connected to Seam. Values are `access_code`, which indicates that the device can manage and utilize digital PIN codes for secure access; `lock`, which indicates that the device controls a door locking mechanism, enabling the remote opening and closing of doors and other entry points; `noise_detection`, which indicates that the device supports monitoring and responding to ambient noise levels; `thermostat`, which indicates that the device can regulate and adjust indoor temperatures; `battery`, which indicates that the device can manage battery life and health; and `phone`, which indicates that the device is a mobile device, such as a smartphone. **Important:** Superseded by [capability flags](https://docs.seam.co/capability-guides/device-and-system-capabilities#capability-flags).
             *
             * @var list<string>|null
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
            public \Seam\Resources\UnmanagedDevice\DeviceType|string|null $device_type,
            /**
             * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
             *
             * @var list<\Seam\Resources\UnmanagedDevice\Errors>
             */
            public array $errors,
            /**
             * Indicates that Seam does not manage the device.
             */
            public false|null $is_managed,
            /**
             * properties of the device.
             */
            public \Seam\Resources\UnmanagedDevice\Properties|null $properties,
            /**
             * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
             *
             * @var list<\Seam\Resources\UnmanagedDevice\Warnings>
             */
            public array $warnings,
            /**
             * Unique identifier for the Seam workspace associated with the device.
             */
            public string|null $workspace_id,
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
             * Location information for the device.
             */
            public \Seam\Resources\UnmanagedDevice\Location|null $location = null,
        ) {}
    }
}

namespace Seam\Resources\UnmanagedDevice {
    /**
     * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it. Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\UnmanagedDevice\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\UnmanagedDevice\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::INSUFFICIENT_PERMISSIONS
                    => \Seam\Resources\UnmanagedDevice\Errors\InsufficientPermissions::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::DORMAKABA_SITES_DISCONNECTED
                    => \Seam\Resources\UnmanagedDevice\Errors\DormakabaSitesDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::DEVICE_OFFLINE
                    => \Seam\Resources\UnmanagedDevice\Errors\DeviceOffline::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::DEVICE_REMOVED
                    => \Seam\Resources\UnmanagedDevice\Errors\DeviceRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::HUB_DISCONNECTED
                    => \Seam\Resources\UnmanagedDevice\Errors\HubDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::DEVICE_DISCONNECTED
                    => \Seam\Resources\UnmanagedDevice\Errors\DeviceDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::EMPTY_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\UnmanagedDevice\Errors\EmptyBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::AUGUST_LOCK_NOT_AUTHORIZED
                    => \Seam\Resources\UnmanagedDevice\Errors\AugustLockNotAuthorized::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::MISSING_DEVICE_CREDENTIALS
                    => \Seam\Resources\UnmanagedDevice\Errors\MissingDeviceCredentials::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::AUXILIARY_HEAT_RUNNING
                    => \Seam\Resources\UnmanagedDevice\Errors\AuxiliaryHeatRunning::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::SUBSCRIPTION_REQUIRED
                    => \Seam\Resources\UnmanagedDevice\Errors\SubscriptionRequired::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\UnmanagedDevice\Errors\BridgeDisconnected::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    error_code: is_string($json->error_code ?? null)
                        ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                                $json->error_code,
                            ) ?? $json->error_code
                        : null,
                    message: $json->message ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
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
                model: isset($json->model)
                    ? \Seam\Resources\UnmanagedDevice\Properties\Model::from_json(
                        $json->model,
                    )
                    : null,
                name: $json->name ?? null,
                online: $json->online ?? null,
                accessory_keypad: isset($json->accessory_keypad)
                    ? \Seam\Resources\UnmanagedDevice\Properties\AccessoryKeypad::from_json(
                        $json->accessory_keypad,
                    )
                    : null,
                battery: isset($json->battery)
                    ? \Seam\Resources\UnmanagedDevice\Properties\Battery::from_json(
                        $json->battery,
                    )
                    : null,
                battery_level: $json->battery_level ?? null,
                image_alt_text: $json->image_alt_text ?? null,
                image_url: $json->image_url ?? null,
                manufacturer: $json->manufacturer ?? null,
                offline_access_codes_enabled: $json->offline_access_codes_enabled ??
                    null,
                online_access_codes_enabled: $json->online_access_codes_enabled ??
                    null,
            );
        }

        public function __construct(
            /**
             * Device model-related properties.
             */
            public \Seam\Resources\UnmanagedDevice\Properties\Model|null $model,
            /**
             * Name of the device.
             *
             * @deprecated use device.display_name instead
             */
            public string|null $name,
            /**
             * Indicates whether the device is online.
             */
            public bool|null $online,
            /**
             * Accessory keypad properties and state.
             */
            public \Seam\Resources\UnmanagedDevice\Properties\AccessoryKeypad|null $accessory_keypad = null,
            /**
             * Represents the current status of the battery charge level.
             */
            public \Seam\Resources\UnmanagedDevice\Properties\Battery|null $battery = null,
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
             * Indicates whether it is currently possible to use offline access codes for the device.
             *
             * @deprecated use device.can_program_offline_access_codes
             */
            public bool|null $offline_access_codes_enabled = null,
            /**
             * Indicates whether it is currently possible to use online access codes for the device.
             *
             * @deprecated use device.can_program_online_access_codes
             */
            public bool|null $online_access_codes_enabled = null,
        ) {}
    }

    /**
     * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it. Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::PARTIAL_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\UnmanagedDevice\Warnings\PartialBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::MANY_ACTIVE_BACKUP_CODES
                    => \Seam\Resources\UnmanagedDevice\Warnings\ManyActiveBackupCodes::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::THIRD_PARTY_INTEGRATION_DETECTED
                    => \Seam\Resources\UnmanagedDevice\Warnings\ThirdPartyIntegrationDetected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TTLOCK_LOCK_GATEWAY_UNLOCKING_NOT_ENABLED
                    => \Seam\Resources\UnmanagedDevice\Warnings\TtlockLockGatewayUnlockingNotEnabled::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TTLOCK_WEAK_GATEWAY_SIGNAL
                    => \Seam\Resources\UnmanagedDevice\Warnings\TtlockWeakGatewaySignal::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::POWER_SAVING_MODE
                    => \Seam\Resources\UnmanagedDevice\Warnings\PowerSavingMode::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TEMPERATURE_THRESHOLD_EXCEEDED
                    => \Seam\Resources\UnmanagedDevice\Warnings\TemperatureThresholdExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::DEVICE_COMMUNICATION_DEGRADED
                    => \Seam\Resources\UnmanagedDevice\Warnings\DeviceCommunicationDegraded::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::SCHEDULED_MAINTENANCE_WINDOW
                    => \Seam\Resources\UnmanagedDevice\Warnings\ScheduledMaintenanceWindow::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::DEVICE_HAS_FLAKY_CONNECTION
                    => \Seam\Resources\UnmanagedDevice\Warnings\DeviceHasFlakyConnection::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::SALTO_KS_OFFICE_MODE
                    => \Seam\Resources\UnmanagedDevice\Warnings\SaltoKsOfficeMode::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::SALTO_KS_PRIVACY_MODE
                    => \Seam\Resources\UnmanagedDevice\Warnings\SaltoKsPrivacyMode::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::PRIVACY_MODE
                    => \Seam\Resources\UnmanagedDevice\Warnings\PrivacyMode::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED
                    => \Seam\Resources\UnmanagedDevice\Warnings\SaltoKsSubscriptionLimitAlmostReached::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::SALTO_KS_LOCK_ACCESS_CODE_SUPPORT_REMOVED
                    => \Seam\Resources\UnmanagedDevice\Warnings\SaltoKsLockAccessCodeSupportRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_PHONE
                    => \Seam\Resources\UnmanagedDevice\Warnings\UnknownIssueWithPhone::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::LOCKLY_TIME_ZONE_NOT_CONFIGURED
                    => \Seam\Resources\UnmanagedDevice\Warnings\LocklyTimeZoneNotConfigured::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::ULTRALOQ_TIME_ZONE_UNKNOWN
                    => \Seam\Resources\UnmanagedDevice\Warnings\UltraloqTimeZoneUnknown::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TIME_ZONE_UNKNOWN
                    => \Seam\Resources\UnmanagedDevice\Warnings\TimeZoneUnknown::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TIME_ZONE_MISMATCH
                    => \Seam\Resources\UnmanagedDevice\Warnings\TimeZoneMismatch::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::TWO_N_DEVICE_MISSING_TIMEZONE
                    => \Seam\Resources\UnmanagedDevice\Warnings\TwoNDeviceMissingTimezone::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::HUB_REQUIRED_FOR_ADDITIONAL_CAPABILITIES
                    => \Seam\Resources\UnmanagedDevice\Warnings\HubRequiredForAdditionalCapabilities::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::PROVIDER_ISSUE
                    => \Seam\Resources\UnmanagedDevice\Warnings\ProviderIssue::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::KEYNEST_UNSUPPORTED_LOCKER
                    => \Seam\Resources\UnmanagedDevice\Warnings\KeynestUnsupportedLocker::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::ACCESSORY_KEYPAD_SETUP_REQUIRED
                    => \Seam\Resources\UnmanagedDevice\Warnings\AccessoryKeypadSetupRequired::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::UNRELIABLE_ONLINE_STATUS
                    => \Seam\Resources\UnmanagedDevice\Warnings\UnreliableOnlineStatus::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::MAX_ACCESS_CODES_REACHED
                    => \Seam\Resources\UnmanagedDevice\Warnings\MaxAccessCodesReached::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    warning_code: is_string($json->warning_code ?? null)
                        ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                                $json->warning_code,
                            ) ?? $json->warning_code
                        : null,
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
             */
            public \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {}
    }

    enum DeviceType: string
    {
        case AKUVOX_LOCK = "akuvox_lock";
        case AUGUST_LOCK = "august_lock";
        case BRIVO_ACCESS_POINT = "brivo_access_point";
        case BUTTERFLYMX_PANEL = "butterflymx_panel";
        case AVIGILON_ALTA_ENTRY = "avigilon_alta_entry";
        case DOORKING_LOCK = "doorking_lock";
        case GENIE_DOOR = "genie_door";
        case IGLOO_LOCK = "igloo_lock";
        case LINEAR_LOCK = "linear_lock";
        case LOCKLY_LOCK = "lockly_lock";
        case KWIKSET_LOCK = "kwikset_lock";
        case NUKI_LOCK = "nuki_lock";
        case SALTO_LOCK = "salto_lock";
        case SCHLAGE_LOCK = "schlage_lock";
        case SMARTTHINGS_LOCK = "smartthings_lock";
        case WYZE_LOCK = "wyze_lock";
        case YALE_LOCK = "yale_lock";
        case TWO_N_INTERCOM = "two_n_intercom";
        case CONTROLBYWEB_DEVICE = "controlbyweb_device";
        case TTLOCK_LOCK = "ttlock_lock";
        case IGLOOHOME_LOCK = "igloohome_lock";
        case FOUR_SUITES_DOOR = "four_suites_door";
        case DORMAKABA_ORACODE_DOOR = "dormakaba_oracode_door";
        case TEDEE_LOCK = "tedee_lock";
        case AKILES_LOCK = "akiles_lock";
        case ULTRALOQ_LOCK = "ultraloq_lock";
        case YACAN_LOCK = "yacan_lock";
        case KEYINCODE_LOCK = "keyincode_lock";
        case OMNITEC_LOCK = "omnitec_lock";
        case KISI_LOCK = "kisi_lock";
        case AQARA_LOCK = "aqara_lock";
        case KEYNEST_KEY = "keynest_key";
        case NOISEAWARE_ACTIVITY_ZONE = "noiseaware_activity_zone";
        case MINUT_SENSOR = "minut_sensor";
        case ECOBEE_THERMOSTAT = "ecobee_thermostat";
        case NEST_THERMOSTAT = "nest_thermostat";
        case HONEYWELL_RESIDEO_THERMOSTAT = "honeywell_resideo_thermostat";
        case TADO_THERMOSTAT = "tado_thermostat";
        case SENSI_THERMOSTAT = "sensi_thermostat";
        case SMARTTHINGS_THERMOSTAT = "smartthings_thermostat";
        case IOS_PHONE = "ios_phone";
        case ANDROID_PHONE = "android_phone";
        case RING_CAMERA = "ring_camera";
    }
}

namespace Seam\Resources\UnmanagedDevice\Errors {
    /**
     * Indicates that the account is disconnected.
     */
    final class AccountDisconnected extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): AccountDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://docs.seam.co/api/connected_accounts) error.
             */
            public true|null $is_connected_account_error,
            /**
             * Indicates that the error is not a device error.
             */
            public false|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the Salto site user limit has been reached.
     */
    final class SaltoKsSubscriptionLimitExceeded extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsSubscriptionLimitExceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://docs.seam.co/api/connected_accounts) error.
             */
            public true|null $is_connected_account_error,
            /**
             * Indicates that the error is not a device error.
             */
            public false|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that Seam's integration user does not have sufficient permissions on the provider's system to which this device belongs, so Seam cannot manage access codes or unlock the device. See the error message for specifics, then either reauthorize the connected account in Seam or grant the integration user the required permissions in the provider's system.
     */
    final class InsufficientPermissions extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): InsufficientPermissions|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://docs.seam.co/api/connected_accounts) error.
             */
            public true|null $is_connected_account_error,
            /**
             * Indicates that the error is not a device error.
             */
            public false|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that one or more dormakaba sites associated with the connected account could not be connected. Contact dormakaba support.
     */
    final class DormakabaSitesDisconnected extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): DormakabaSitesDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://docs.seam.co/api/connected_accounts) error.
             */
            public true|null $is_connected_account_error,
            /**
             * Indicates that the error is not a device error.
             */
            public false|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the device is offline.
     */
    final class DeviceOffline extends \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): DeviceOffline|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the device has been removed.
     */
    final class DeviceRemoved extends \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): DeviceRemoved|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the hub is disconnected.
     */
    final class HubDisconnected extends \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): HubDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the device is disconnected.
     */
    final class DeviceDisconnected extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): DeviceDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is empty.
     */
    final class EmptyBackupAccessCodePool extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): EmptyBackupAccessCodePool|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the user is not authorized to use the August lock.
     */
    final class AugustLockNotAuthorized extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): AugustLockNotAuthorized|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that device credentials are missing.
     */
    final class MissingDeviceCredentials extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(
            mixed $json,
        ): MissingDeviceCredentials|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the auxiliary heat is running.
     */
    final class AuxiliaryHeatRunning extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): AuxiliaryHeatRunning|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that a subscription is required to connect.
     */
    final class SubscriptionRequired extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): SubscriptionRequired|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                is_device_error: $json->is_device_error ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Indicates that the error is a device error.
             */
            public true|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge), for example, if the Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline. See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class BridgeDisconnected extends
        \Seam\Resources\UnmanagedDevice\Errors
    {
        public static function from_json(mixed $json): BridgeDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
                message: $json->message ?? null,
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Errors\ErrorCode|string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            public bool|null $is_connected_account_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    enum ErrorCode: string
    {
        case ACCOUNT_DISCONNECTED = "account_disconnected";
        case SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED = "salto_ks_subscription_limit_exceeded";
        case INSUFFICIENT_PERMISSIONS = "insufficient_permissions";
        case DORMAKABA_SITES_DISCONNECTED = "dormakaba_sites_disconnected";
        case DEVICE_OFFLINE = "device_offline";
        case DEVICE_REMOVED = "device_removed";
        case HUB_DISCONNECTED = "hub_disconnected";
        case DEVICE_DISCONNECTED = "device_disconnected";
        case EMPTY_BACKUP_ACCESS_CODE_POOL = "empty_backup_access_code_pool";
        case AUGUST_LOCK_NOT_AUTHORIZED = "august_lock_not_authorized";
        case MISSING_DEVICE_CREDENTIALS = "missing_device_credentials";
        case AUXILIARY_HEAT_RUNNING = "auxiliary_heat_running";
        case SUBSCRIPTION_REQUIRED = "subscription_required";
        case BRIDGE_DISCONNECTED = "bridge_disconnected";
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
                is_connected: $json->is_connected ?? null,
                battery: isset($json->battery)
                    ? \Seam\Resources\UnmanagedDevice\Properties\AccessoryKeypad\Battery::from_json(
                        $json->battery,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Indicates if an accessory keypad is connected to the device.
             */
            public bool|null $is_connected,
            /**
             * Keypad battery properties.
             */
            public \Seam\Resources\UnmanagedDevice\Properties\AccessoryKeypad\Battery|null $battery = null,
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
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Properties\Battery\Status::tryFrom(
                            $json->status,
                        ) ?? $json->status
                    : null,
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
            public \Seam\Resources\UnmanagedDevice\Properties\Battery\Status|string|null $status,
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
                display_name: $json->display_name ?? null,
                manufacturer_display_name: $json->manufacturer_display_name ??
                    null,
                accessory_keypad_supported: $json->accessory_keypad_supported ??
                    null,
                can_connect_accessory_keypad: $json->can_connect_accessory_keypad ??
                    null,
                has_built_in_keypad: $json->has_built_in_keypad ?? null,
                offline_access_codes_supported: $json->offline_access_codes_supported ??
                    null,
                online_access_codes_supported: $json->online_access_codes_supported ??
                    null,
            );
        }

        public function __construct(
            /**
             * Display name of the device model.
             */
            public string|null $display_name,
            /**
             * Display name that corresponds to the manufacturer-specific terminology for the device.
             */
            public string|null $manufacturer_display_name,
            /**
             * @deprecated use device.properties.model.can_connect_accessory_keypad
             */
            public bool|null $accessory_keypad_supported = null,
            /**
             * Indicates whether the device can connect a accessory keypad.
             */
            public bool|null $can_connect_accessory_keypad = null,
            /**
             * Indicates whether the device has a built in accessory keypad.
             */
            public bool|null $has_built_in_keypad = null,
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

namespace Seam\Resources\UnmanagedDevice\Properties\Battery {
    enum Status: string
    {
        case CRITICAL = "critical";
        case LOW = "low";
        case GOOD = "good";
        case FULL = "full";
    }
}

namespace Seam\Resources\UnmanagedDevice\Warnings {
    /**
     * Indicates that the backup access code is unhealthy.
     */
    final class PartialBackupAccessCodePool extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): PartialBackupAccessCodePool|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that there are too many backup codes.
     */
    final class ManyActiveBackupCodes extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ManyActiveBackupCodes|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that a third-party integration has been detected.
     */
    final class ThirdPartyIntegrationDetected extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ThirdPartyIntegrationDetected|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the Remote Unlock feature is not enabled in the settings."
     */
    final class TtlockLockGatewayUnlockingNotEnabled extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TtlockLockGatewayUnlockingNotEnabled|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the gateway signal is weak.
     */
    final class TtlockWeakGatewaySignal extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TtlockWeakGatewaySignal|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device is in power saving mode and may have limited functionality.
     */
    final class PowerSavingMode extends \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): PowerSavingMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the temperature threshold has been exceeded.
     */
    final class TemperatureThresholdExceeded extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TemperatureThresholdExceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device appears to be unresponsive.
     */
    final class DeviceCommunicationDegraded extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DeviceCommunicationDegraded|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that a scheduled maintenance window has been detected.
     */
    final class ScheduledMaintenanceWindow extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ScheduledMaintenanceWindow|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device has a flaky connection.
     */
    final class DeviceHasFlakyConnection extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DeviceHasFlakyConnection|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the Salto KS lock is in Office Mode. Access Codes will not unlock doors.
     */
    final class SaltoKsOfficeMode extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): SaltoKsOfficeMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the Salto KS lock is in Privacy Mode. Access Codes will not unlock doors.
     */
    final class SaltoKsPrivacyMode extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): SaltoKsPrivacyMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the lock is in Privacy Mode. Access codes and remote unlock are blocked until Privacy Mode is disabled.
     */
    final class PrivacyMode extends \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): PrivacyMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the Salto KS site has exceeded 80% of the maximum number of allowed users. Increase your subscription limit or delete some users from your site.
     */
    final class SaltoKsSubscriptionLimitAlmostReached extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsSubscriptionLimitAlmostReached|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that a change in the reported device model has been detected for this Salto KS lock, which may occur after an IQ hub reset. Access code support may be affected. See https://help.getseam.com/articles/5098842588-salto-ks-lock-loses-access-code-support for troubleshooting steps.
     */
    final class SaltoKsLockAccessCodeSupportRemoved extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsLockAccessCodeSupportRemoved|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that an unknown issue occurred while syncing the state of the phone with the provider. This issue may affect the proper functioning of the phone.
     */
    final class UnknownIssueWithPhone extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnknownIssueWithPhone|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that Seam detected that the Lockly device does not have a time zone configured. Time-bound codes may not work as expected.
     */
    final class LocklyTimeZoneNotConfigured extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): LocklyTimeZoneNotConfigured|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that Seam does not know the time zone of the Ultraloq device. Set a time zone to enable time-bound access codes.
     */
    final class UltraloqTimeZoneUnknown extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UltraloqTimeZoneUnknown|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that Seam does not know the device's time zone. Set a time zone to enable time-bound access codes.
     */
    final class TimeZoneUnknown extends \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): TimeZoneUnknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device's configured time zone does not match its hardware UTC offset. Time-bound access codes may activate at the wrong local time.
     */
    final class TimeZoneMismatch extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): TimeZoneMismatch|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the 2N device does not have a time zone configured. Configure a time zone on the device to enable access codes.
     */
    final class TwoNDeviceMissingTimezone extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TwoNDeviceMissingTimezone|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that a hub or relay must be connected to unlock additional capabilities such as remote unlock.
     */
    final class HubRequiredForAdditionalCapabilities extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): HubRequiredForAdditionalCapabilities|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates a provider-specific issue that may affect device functionality.
     */
    final class ProviderIssue extends \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(mixed $json): ProviderIssue|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the key is in a locker that does not support the access codes API.
     */
    final class KeynestUnsupportedLocker extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): KeynestUnsupportedLocker|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the accessory keypad exists, but is not linked to the Igloohome Bridge. Online access code programming will fail until the keypad is linked to the Igloohome Bridge in the Igloohome app.
     */
    final class AccessoryKeypadSetupRequired extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): AccessoryKeypadSetupRequired|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device may optimistically be reported as online because the provider does not reliably report its online status.
     */
    final class UnreliableOnlineStatus extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnreliableOnlineStatus|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the device has reached its maximum number of active access codes. Delete existing codes before creating new ones.
     */
    final class MaxAccessCodesReached extends
        \Seam\Resources\UnmanagedDevice\Warnings
    {
        public static function from_json(
            mixed $json,
        ): MaxAccessCodesReached|null {
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
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\UnmanagedDevice\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
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
            string|null $created_at,
            /**
             * Maximum number of active access codes supported by the device.
             */
            public int|null $max_active_access_code_count,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            \Seam\Resources\UnmanagedDevice\Warnings\WarningCode|string|null $warning_code,
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
        case PARTIAL_BACKUP_ACCESS_CODE_POOL = "partial_backup_access_code_pool";
        case MANY_ACTIVE_BACKUP_CODES = "many_active_backup_codes";
        case THIRD_PARTY_INTEGRATION_DETECTED = "third_party_integration_detected";
        case TTLOCK_LOCK_GATEWAY_UNLOCKING_NOT_ENABLED = "ttlock_lock_gateway_unlocking_not_enabled";
        case TTLOCK_WEAK_GATEWAY_SIGNAL = "ttlock_weak_gateway_signal";
        case POWER_SAVING_MODE = "power_saving_mode";
        case TEMPERATURE_THRESHOLD_EXCEEDED = "temperature_threshold_exceeded";
        case DEVICE_COMMUNICATION_DEGRADED = "device_communication_degraded";
        case SCHEDULED_MAINTENANCE_WINDOW = "scheduled_maintenance_window";
        case DEVICE_HAS_FLAKY_CONNECTION = "device_has_flaky_connection";
        case SALTO_KS_OFFICE_MODE = "salto_ks_office_mode";
        case SALTO_KS_PRIVACY_MODE = "salto_ks_privacy_mode";
        case PRIVACY_MODE = "privacy_mode";
        case SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED = "salto_ks_subscription_limit_almost_reached";
        case SALTO_KS_LOCK_ACCESS_CODE_SUPPORT_REMOVED = "salto_ks_lock_access_code_support_removed";
        case UNKNOWN_ISSUE_WITH_PHONE = "unknown_issue_with_phone";
        case LOCKLY_TIME_ZONE_NOT_CONFIGURED = "lockly_time_zone_not_configured";
        case ULTRALOQ_TIME_ZONE_UNKNOWN = "ultraloq_time_zone_unknown";
        case TIME_ZONE_UNKNOWN = "time_zone_unknown";
        case TIME_ZONE_MISMATCH = "time_zone_mismatch";
        case TWO_N_DEVICE_MISSING_TIMEZONE = "two_n_device_missing_timezone";
        case HUB_REQUIRED_FOR_ADDITIONAL_CAPABILITIES = "hub_required_for_additional_capabilities";
        case PROVIDER_ISSUE = "provider_issue";
        case KEYNEST_UNSUPPORTED_LOCKER = "keynest_unsupported_locker";
        case ACCESSORY_KEYPAD_SETUP_REQUIRED = "accessory_keypad_setup_required";
        case UNRELIABLE_ONLINE_STATUS = "unreliable_online_status";
        case MAX_ACCESS_CODES_REACHED = "max_access_codes_reached";
    }
}
