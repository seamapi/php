<?php

namespace Seam\Resources {
    /**
     * Represents an app user's mobile phone.
     */
    class Phone
    {
        public static function from_json(mixed $json): Phone|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                custom_metadata: $json->custom_metadata ?? null,
                device_id: $json->device_id ?? null,
                device_type: $json->device_type ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\Phone\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                properties: isset($json->properties)
                    ? \Seam\Resources\Phone\Properties::from_json(
                        $json->properties,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\Phone\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                nickname: $json->nickname ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the phone was created.
             */
            public string|null $created_at,
            /**
             * Optional [custom metadata](https://www.seam.co/docs/core-concepts/devices/adding-custom-metadata-to-a-device) for the phone.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $custom_metadata,
            /**
             * ID of the phone.
             */
            public string|null $device_id,
            /**
             * Type of the phone device, such as `ios_phone` or `android_phone`.
             *
             * @var value-of<\Seam\Resources\Phone\DeviceType>|string|null
             */
            public string|null $device_type,
            /**
             * Display name of the phone. Defaults to `nickname` (if it is set) or `properties.appearance.name`, otherwise. Enables administrators and users to identify the phone easily, especially when there are numerous phones.
             */
            public string|null $display_name,
            /**
             * Array of errors associated with the device. Each error object within the array contains two fields: `error_code` and `message`. `error_code` is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
             *
             * @var list<\Seam\Resources\Phone\Errors>
             */
            public array $errors,
            /**
             * Properties of the phone.
             */
            public \Seam\Resources\Phone\Properties|null $properties,
            /**
             * Array of warnings associated with the device. Each warning object within the array contains two fields: `warning_code` and `message`. `warning_code` is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. `message` provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
             *
             * @var list<\Seam\Resources\Phone\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the phone.
             */
            public string|null $workspace_id,
            /**
             * Optional nickname to describe the phone, settable through Seam.
             */
            public string|null $nickname = null,
        ) {}
    }
}

namespace Seam\Resources\Phone {
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
                ? \Seam\Resources\Phone\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\Phone\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\Phone\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\Phone\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::INSUFFICIENT_PERMISSIONS
                    => \Seam\Resources\Phone\Errors\InsufficientPermissions::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::DORMAKABA_SITES_DISCONNECTED
                    => \Seam\Resources\Phone\Errors\DormakabaSitesDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::DEVICE_OFFLINE
                    => \Seam\Resources\Phone\Errors\DeviceOffline::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::DEVICE_REMOVED
                    => \Seam\Resources\Phone\Errors\DeviceRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::HUB_DISCONNECTED
                    => \Seam\Resources\Phone\Errors\HubDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::DEVICE_DISCONNECTED
                    => \Seam\Resources\Phone\Errors\DeviceDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::EMPTY_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\Phone\Errors\EmptyBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::AUGUST_LOCK_NOT_AUTHORIZED
                    => \Seam\Resources\Phone\Errors\AugustLockNotAuthorized::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::MISSING_DEVICE_CREDENTIALS
                    => \Seam\Resources\Phone\Errors\MissingDeviceCredentials::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::AUXILIARY_HEAT_RUNNING
                    => \Seam\Resources\Phone\Errors\AuxiliaryHeatRunning::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::SUBSCRIPTION_REQUIRED
                    => \Seam\Resources\Phone\Errors\SubscriptionRequired::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\Phone\Errors\BridgeDisconnected::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Properties of the phone.
     */
    class Properties
    {
        public static function from_json(mixed $json): Properties|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                assa_abloy_credential_service_metadata: isset(
                    $json->assa_abloy_credential_service_metadata,
                )
                    ? \Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata::from_json(
                        $json->assa_abloy_credential_service_metadata,
                    )
                    : null,
                salto_space_credential_service_metadata: isset(
                    $json->salto_space_credential_service_metadata,
                )
                    ? \Seam\Resources\Phone\Properties\SaltoSpaceCredentialServiceMetadata::from_json(
                        $json->salto_space_credential_service_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ASSA ABLOY Credential Service metadata for the phone.
             */
            public \Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata = null,
            /**
             * Salto Space credential service metadata for the phone.
             */
            public \Seam\Resources\Phone\Properties\SaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata = null,
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
                ? \Seam\Resources\Phone\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\Phone\Warnings\WarningCode::PARTIAL_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\Phone\Warnings\PartialBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::MANY_ACTIVE_BACKUP_CODES
                    => \Seam\Resources\Phone\Warnings\ManyActiveBackupCodes::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::THIRD_PARTY_INTEGRATION_DETECTED
                    => \Seam\Resources\Phone\Warnings\ThirdPartyIntegrationDetected::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TTLOCK_LOCK_GATEWAY_UNLOCKING_NOT_ENABLED
                    => \Seam\Resources\Phone\Warnings\TtlockLockGatewayUnlockingNotEnabled::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TTLOCK_WEAK_GATEWAY_SIGNAL
                    => \Seam\Resources\Phone\Warnings\TtlockWeakGatewaySignal::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::POWER_SAVING_MODE
                    => \Seam\Resources\Phone\Warnings\PowerSavingMode::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TEMPERATURE_THRESHOLD_EXCEEDED
                    => \Seam\Resources\Phone\Warnings\TemperatureThresholdExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::DEVICE_COMMUNICATION_DEGRADED
                    => \Seam\Resources\Phone\Warnings\DeviceCommunicationDegraded::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::SCHEDULED_MAINTENANCE_WINDOW
                    => \Seam\Resources\Phone\Warnings\ScheduledMaintenanceWindow::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::DEVICE_HAS_FLAKY_CONNECTION
                    => \Seam\Resources\Phone\Warnings\DeviceHasFlakyConnection::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::SALTO_KS_OFFICE_MODE
                    => \Seam\Resources\Phone\Warnings\SaltoKsOfficeMode::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::SALTO_KS_PRIVACY_MODE
                    => \Seam\Resources\Phone\Warnings\SaltoKsPrivacyMode::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::PRIVACY_MODE
                    => \Seam\Resources\Phone\Warnings\PrivacyMode::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED
                    => \Seam\Resources\Phone\Warnings\SaltoKsSubscriptionLimitAlmostReached::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::SALTO_KS_LOCK_ACCESS_CODE_SUPPORT_REMOVED
                    => \Seam\Resources\Phone\Warnings\SaltoKsLockAccessCodeSupportRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_PHONE
                    => \Seam\Resources\Phone\Warnings\UnknownIssueWithPhone::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::LOCKLY_TIME_ZONE_NOT_CONFIGURED
                    => \Seam\Resources\Phone\Warnings\LocklyTimeZoneNotConfigured::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::ULTRALOQ_TIME_ZONE_UNKNOWN
                    => \Seam\Resources\Phone\Warnings\UltraloqTimeZoneUnknown::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TIME_ZONE_UNKNOWN
                    => \Seam\Resources\Phone\Warnings\TimeZoneUnknown::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TIME_ZONE_MISMATCH
                    => \Seam\Resources\Phone\Warnings\TimeZoneMismatch::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::TWO_N_DEVICE_MISSING_TIMEZONE
                    => \Seam\Resources\Phone\Warnings\TwoNDeviceMissingTimezone::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::HUB_REQUIRED_FOR_ADDITIONAL_CAPABILITIES
                    => \Seam\Resources\Phone\Warnings\HubRequiredForAdditionalCapabilities::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::PROVIDER_ISSUE
                    => \Seam\Resources\Phone\Warnings\ProviderIssue::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::KEYNEST_UNSUPPORTED_LOCKER
                    => \Seam\Resources\Phone\Warnings\KeynestUnsupportedLocker::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::ACCESSORY_KEYPAD_SETUP_REQUIRED
                    => \Seam\Resources\Phone\Warnings\AccessoryKeypadSetupRequired::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::ACCESSORY_KEYPAD_LOW_BATTERY
                    => \Seam\Resources\Phone\Warnings\AccessoryKeypadLowBattery::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::UNRELIABLE_ONLINE_STATUS
                    => \Seam\Resources\Phone\Warnings\UnreliableOnlineStatus::from_json(
                    $json,
                ),
                \Seam\Resources\Phone\Warnings\WarningCode::MAX_ACCESS_CODES_REACHED
                    => \Seam\Resources\Phone\Warnings\MaxAccessCodesReached::from_json(
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }

    enum DeviceType: string
    {
        case IOS_PHONE = "ios_phone";
        case ANDROID_PHONE = "android_phone";
    }
}

namespace Seam\Resources\Phone\Errors {
    /**
     * Indicates that the account is disconnected.
     */
    final class AccountDisconnected extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): AccountDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://www.seam.co/docs/api/connected_accounts/object) error.
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
        \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsSubscriptionLimitExceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://www.seam.co/docs/api/connected_accounts/object) error.
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
     * Indicates that the provider's system to which this device belongs is not letting Seam act on it, so Seam cannot manage access codes or unlock the device. The error message says which of three causes applies. Seam's integration user may not have sufficient permissions on the provider's system, or may have been suspended there: grant the integration user the required permissions in the provider's system, or reauthorize the connected account in Seam. The provider account or site may not have a valid subscription with the provider: set up or renew that subscription with the provider. Or the provider may have rejected the credential Seam uses to operate the device's hub: contact Seam support to re-activate it.
     */
    final class InsufficientPermissions extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): InsufficientPermissions|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://www.seam.co/docs/api/connected_accounts/object) error.
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
    final class DormakabaSitesDisconnected extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): DormakabaSitesDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that the error is a [connected account](https://www.seam.co/docs/api/connected_accounts/object) error.
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
    final class DeviceOffline extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): DeviceOffline|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class DeviceRemoved extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): DeviceRemoved|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class HubDisconnected extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): HubDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class DeviceDisconnected extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): DeviceDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
     * Indicates that the [backup access code pool](https://www.seam.co/docs/low-level-apis/smart-locks/access-codes/backup-access-codes) is empty.
     */
    final class EmptyBackupAccessCodePool extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): EmptyBackupAccessCodePool|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class AugustLockNotAuthorized extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): AugustLockNotAuthorized|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class MissingDeviceCredentials extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(
            mixed $json,
        ): MissingDeviceCredentials|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class AuxiliaryHeatRunning extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): AuxiliaryHeatRunning|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
    final class SubscriptionRequired extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): SubscriptionRequired|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://www.seam.co/docs/capability-guides/seam-bridge), for example, if the Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline. See also [Troubleshooting Your Access Control System](https://www.seam.co/docs/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class BridgeDisconnected extends \Seam\Resources\Phone\Errors
    {
        public static function from_json(mixed $json): BridgeDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\Phone\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://www.seam.co/docs/capability-guides/seam-bridge).
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

namespace Seam\Resources\Phone\Properties {
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
                    ) => \Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata\Endpoints::from_json(
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
             *
             * @var list<\Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata\Endpoints>|null
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
}

namespace Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata {
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

namespace Seam\Resources\Phone\Warnings {
    /**
     * Indicates that the backup access code is unhealthy.
     */
    final class PartialBackupAccessCodePool extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that there are too many backup codes.
     */
    final class ManyActiveBackupCodes extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that a third-party integration has been detected.
     */
    final class ThirdPartyIntegrationDetected extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the Remote Unlock feature is not enabled in the settings."
     */
    final class TtlockLockGatewayUnlockingNotEnabled extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the gateway signal is weak.
     */
    final class TtlockWeakGatewaySignal extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device is in power saving mode and may have limited functionality.
     */
    final class PowerSavingMode extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): PowerSavingMode|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the temperature threshold has been exceeded.
     */
    final class TemperatureThresholdExceeded extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device appears to be unresponsive.
     */
    final class DeviceCommunicationDegraded extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that a scheduled maintenance window has been detected.
     */
    final class ScheduledMaintenanceWindow extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device has a flaky connection.
     */
    final class DeviceHasFlakyConnection extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the Salto KS lock is in Office Mode. Access Codes will not unlock doors.
     */
    final class SaltoKsOfficeMode extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): SaltoKsOfficeMode|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the Salto KS lock is in Privacy Mode. Access Codes will not unlock doors.
     */
    final class SaltoKsPrivacyMode extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): SaltoKsPrivacyMode|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the lock is in Privacy Mode. Access codes and remote unlock are blocked until Privacy Mode is disabled.
     */
    final class PrivacyMode extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): PrivacyMode|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the Salto KS site has exceeded 80% of the maximum number of allowed users. Increase your subscription limit or delete some users from your site.
     */
    final class SaltoKsSubscriptionLimitAlmostReached extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that a change in the reported device model has been detected for this Salto KS lock, which may occur after an IQ hub reset. Access code support may be affected. See https://help.getseam.com/articles/5098842588-salto-ks-lock-loses-access-code-support for troubleshooting steps.
     */
    final class SaltoKsLockAccessCodeSupportRemoved extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that an unknown issue occurred while syncing the state of the phone with the provider. This issue may affect the proper functioning of the phone.
     */
    final class UnknownIssueWithPhone extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that Seam detected that the Lockly device does not have a time zone configured. Time-bound codes may not work as expected.
     */
    final class LocklyTimeZoneNotConfigured extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that Seam does not know the time zone of the Ultraloq device. Set a time zone to enable time-bound access codes.
     */
    final class UltraloqTimeZoneUnknown extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that Seam does not know the device's time zone. Set a time zone to enable time-bound access codes.
     */
    final class TimeZoneUnknown extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): TimeZoneUnknown|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device's configured time zone does not match its hardware UTC offset. Time-bound access codes may activate at the wrong local time.
     */
    final class TimeZoneMismatch extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): TimeZoneMismatch|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the 2N device does not have a time zone configured. Configure a time zone on the device to enable access codes.
     */
    final class TwoNDeviceMissingTimezone extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that a hub or relay must be connected to unlock additional capabilities such as remote unlock.
     */
    final class HubRequiredForAdditionalCapabilities extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates a provider-specific issue that may affect device functionality.
     */
    final class ProviderIssue extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(mixed $json): ProviderIssue|null
        {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the key is in a locker that does not support the access codes API.
     */
    final class KeynestUnsupportedLocker extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the accessory keypad exists, but is not linked to the Igloohome Bridge. Online access code programming will fail until the keypad is linked to the Igloohome Bridge in the Igloohome app.
     */
    final class AccessoryKeypadSetupRequired extends
        \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the accessory keypad paired with this lock has a low or critically low battery. Replace its batteries so guests can keep entering their access codes.
     */
    final class AccessoryKeypadLowBattery extends \Seam\Resources\Phone\Warnings
    {
        public static function from_json(
            mixed $json,
        ): AccessoryKeypadLowBattery|null {
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device may optimistically be reported as online because the provider does not reliably report its online status.
     */
    final class UnreliableOnlineStatus extends \Seam\Resources\Phone\Warnings
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
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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

    /**
     * Indicates that the device has reached its maximum number of active access codes. Delete existing codes before creating new ones.
     */
    final class MaxAccessCodesReached extends \Seam\Resources\Phone\Warnings
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
             *
             * @var value-of<\Seam\Resources\Phone\Warnings\WarningCode>|string|null
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
        case ACCESSORY_KEYPAD_LOW_BATTERY = "accessory_keypad_low_battery";
        case UNRELIABLE_ONLINE_STATUS = "unreliable_online_status";
        case MAX_ACCESS_CODES_REACHED = "max_access_codes_reached";
    }
}
