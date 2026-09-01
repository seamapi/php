<?php

namespace Seam\Resources {
    /**
     * Represents an [unmanaged smart lock access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes).
     *
     * An access code is a code used for a keypad or pinpad device. Unlike physical keys, which can easily be lost or duplicated, PIN codes can be customized, tracked, and altered on the fly.
     *
     * When you create an access code on a device in Seam, it is created as a managed access code. Access codes that exist on a device that were not created through Seam are considered unmanaged codes. We strictly limit the operations that can be performed on unmanaged codes.
     *
     * Prior to using Seam to manage your devices, you may have used another lock management system to manage the access codes on your devices. Where possible, we help you keep any existing access codes on devices and transition those codes to ones managed by your Seam workspace.
     *
     * Not all providers support unmanaged access codes. The following providers do not support unmanaged access codes:
     *
     * - [Kwikset](https://docs.seam.co/device-and-system-integration-guides/kwikset-locks)
     */
    class UnmanagedAccessCode
    {
        public static function from_json(mixed $json): UnmanagedAccessCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                code: $json->code ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                errors: \Seam\Parse::to_list(
                    $json->errors ?? null,
                    fn(
                        $e,
                    ) => \Seam\Resources\UnmanagedAccessCode\Errors::from_json(
                        $e,
                    ),
                ),
                is_managed: $json->is_managed ?? null,
                name: $json->name ?? null,
                status: $json->status ?? null,
                type: $json->type ?? null,
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\UnmanagedAccessCode\Warnings::from_json(
                        $w,
                    ),
                ),
                workspace_id: $json->workspace_id ?? null,
                cannot_be_managed: $json->cannot_be_managed ?? null,
                cannot_delete_unmanaged_access_code: $json->cannot_delete_unmanaged_access_code ??
                    null,
                dormakaba_oracode_metadata: isset(
                    $json->dormakaba_oracode_metadata,
                )
                    ? \Seam\Resources\UnmanagedAccessCode\DormakabaOracodeMetadata::from_json(
                        $json->dormakaba_oracode_metadata,
                    )
                    : null,
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier for the access code.
             */
            public string|null $access_code_id,
            /**
             * Code used for access. Typically, a numeric or alphanumeric string.
             */
            public string|null $code,
            /**
             * Date and time at which the access code was created.
             */
            public string|null $created_at,
            /**
             * Unique identifier for the device associated with the access code.
             */
            public string|null $device_id,
            /**
             * Errors associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
             *
             * @var list<\Seam\Resources\UnmanagedAccessCode\Errors>
             */
            public array $errors,
            /**
             * Indicates that Seam does not manage the access code.
             */
            public false|null $is_managed,
            /**
             * Name of the access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes. Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`. To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints. To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
             */
            public string|null $name,
            /**
             * Current status of the access code within the operational lifecycle. `set` indicates that the code is active and operational. `unset` indicates that the code exists on the provider but is not usable on the device.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Status>|string|null
             */
            public string|null $status,
            /**
             * Type of the access code. `ongoing` access codes are active continuously until deactivated manually. `time_bound` access codes have a specific duration.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Type>|string|null
             */
            public string|null $type,
            /**
             * Warnings associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
             *
             * @var list<\Seam\Resources\UnmanagedAccessCode\Warnings>
             */
            public array $warnings,
            /**
             * Unique identifier for the Seam workspace associated with the access code.
             */
            public string|null $workspace_id,
            /**
             * Indicates that Seam cannot convert this unmanaged access code to a managed access code. Some providers do not support management of unmanaged access codes through API integrations.
             */
            public true|null $cannot_be_managed = null,
            /**
             * Indicates that Seam cannot delete this unmanaged access code through the provider. If this access code needs to be deleted, it will only be possible from the manufacturer app.
             */
            public true|null $cannot_delete_unmanaged_access_code = null,
            /**
             * Metadata for a dormakaba Oracode unmanaged access code. Only present for unmanaged access codes from dormakaba Oracode devices.
             */
            public \Seam\Resources\UnmanagedAccessCode\DormakabaOracodeMetadata|null $dormakaba_oracode_metadata = null,
            /**
             * Date and time after which the time-bound access code becomes inactive.
             */
            public string|null $ends_at = null,
            /**
             * Date and time at which the time-bound access code becomes active.
             */
            public string|null $starts_at = null,
        ) {}
    }
}

namespace Seam\Resources\UnmanagedAccessCode {
    /**
     * Metadata for a dormakaba Oracode unmanaged access code. Only present for unmanaged access codes from dormakaba Oracode devices.
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
                is_cancellable: $json->is_cancellable ?? null,
                is_early_checkin_able: $json->is_early_checkin_able ?? null,
                is_extendable: $json->is_extendable ?? null,
                is_overridable: $json->is_overridable ?? null,
                site_name: $json->site_name ?? null,
                stay_id: $json->stay_id ?? null,
                user_level_id: $json->user_level_id ?? null,
                user_level_name: $json->user_level_name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the stay can be cancelled via the Dormakaba Oracode API.
             */
            public bool|null $is_cancellable = null,
            /**
             * Indicates whether early check-in is available for this stay.
             */
            public bool|null $is_early_checkin_able = null,
            /**
             * Indicates whether the stay can be extended via the Dormakaba Oracode API.
             */
            public bool|null $is_extendable = null,
            /**
             * Indicates whether the access code can be overridden. When false, the maximum number of overrides has been reached.
             */
            public bool|null $is_overridable = null,
            /**
             * Dormakaba Oracode site name associated with this access code.
             */
            public string|null $site_name = null,
            /**
             * Dormakaba Oracode stay ID associated with this access code.
             */
            public float|null $stay_id = null,
            /**
             * Dormakaba Oracode user level ID associated with this access code.
             */
            public string|null $user_level_id = null,
            /**
             * Dormakaba Oracode user level name associated with this access code.
             */
            public string|null $user_level_name = null,
        ) {}
    }

    /**
     * Errors associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes). Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::PROVIDER_ISSUE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\ProviderIssue::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::FAILED_TO_SET_ON_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\FailedToSetOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::FAILED_TO_REMOVE_FROM_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\FailedToRemoveFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::DUPLICATE_CODE_ON_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\DuplicateCodeOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::NO_SPACE_FOR_ACCESS_CODE_ON_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\NoSpaceForAccessCodeOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::CONFLICTING_EXTERNAL_MODIFICATION
                    => \Seam\Resources\UnmanagedAccessCode\Errors\ConflictingExternalModification::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::ACCESS_CODE_INACTIVE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\AccessCodeInactive::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::CODE_CONSTRAINTS_VIOLATED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\CodeConstraintsViolated::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::FAILED_TO_ISSUE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\FailedToIssue::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::FAILED_TO_UPDATE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\FailedToUpdate::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::FAILED_TO_EXPIRE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\FailedToExpire::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::INSUFFICIENT_PERMISSIONS
                    => \Seam\Resources\UnmanagedAccessCode\Errors\InsufficientPermissions::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::DORMAKABA_SITES_DISCONNECTED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\DormakabaSitesDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::DEVICE_OFFLINE
                    => \Seam\Resources\UnmanagedAccessCode\Errors\DeviceOffline::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::DEVICE_REMOVED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\DeviceRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::HUB_DISCONNECTED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\HubDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::DEVICE_DISCONNECTED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\DeviceDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::EMPTY_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\UnmanagedAccessCode\Errors\EmptyBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::AUGUST_LOCK_NOT_AUTHORIZED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\AugustLockNotAuthorized::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::MISSING_DEVICE_CREDENTIALS
                    => \Seam\Resources\UnmanagedAccessCode\Errors\MissingDeviceCredentials::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::AUXILIARY_HEAT_RUNNING
                    => \Seam\Resources\UnmanagedAccessCode\Errors\AuxiliaryHeatRunning::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::SUBSCRIPTION_REQUIRED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\SubscriptionRequired::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\UnmanagedAccessCode\Errors\BridgeDisconnected::from_json(
                    $json,
                ),
                default => new self(
                    error_code: $json->error_code ?? null,
                    message: $json->message ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes). Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::CODE_ROTATES_PERIODICALLY
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\CodeRotatesPeriodically::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::TIME_FRAME_ADJUSTED_FOR_UNKNOWN_TIME_ZONE
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\TimeFrameAdjustedForUnknownTimeZone::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::EXTERNAL_MODIFICATION_IN_EFFECT
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\ExternalModificationInEffect::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::DELAY_IN_SETTING_ON_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\DelayInSettingOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::DELAY_IN_REMOVING_FROM_DEVICE
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\DelayInRemovingFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::DELAY_IN_ISSUING
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\DelayInIssuing::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::THIRD_PARTY_INTEGRATION_DETECTED
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\ThirdPartyIntegrationDetected::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::IGLOO_ALGOPIN_MUST_BE_USED_WITHIN_24_HOURS
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\IglooAlgopinMustBeUsedWithin_24Hours::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::MANAGEMENT_TRANSFERRED
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\ManagementTransferred::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::USING_BACKUP_ACCESS_CODE
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\UsingBackupAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_ACCESS_CODE
                    => \Seam\Resources\UnmanagedAccessCode\Warnings\UnknownIssueWithAccessCode::from_json(
                    $json,
                ),
                default => new self(
                    message: $json->message ?? null,
                    warning_code: $json->warning_code ?? null,
                    created_at: $json->created_at ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at = null,
        ) {}
    }

    enum Status: string
    {
        case SET = "set";
        case VALUE_UNSET = "unset";
    }

    enum Type: string
    {
        case TIME_BOUND = "time_bound";
        case ONGOING = "ongoing";
    }
}

namespace Seam\Resources\UnmanagedAccessCode\Errors {
    /**
     * Indicates a provider-specific issue that prevents the access code from being set or managed. Check the error message for details.
     */
    final class ProviderIssue extends \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): ProviderIssue|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Failed to set code on device.
     */
    final class FailedToSetOnDevice extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): FailedToSetOnDevice|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Failed to remove code from device.
     */
    final class FailedToRemoveFromDevice extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(
            mixed $json,
        ): FailedToRemoveFromDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Duplicate access code detected on device.
     */
    final class DuplicateCodeOnDevice extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(
            mixed $json,
        ): DuplicateCodeOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
                managed_access_code_id: $json->managed_access_code_id ?? null,
                unmanaged_access_code_id: $json->unmanaged_access_code_id ??
                    null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
            /**
             * ID of the managed access code that conflicts with this managed access code, when Seam can identify it.
             */
            public string|null $managed_access_code_id = null,
            /**
             * ID of the unmanaged access code that conflicts with this managed access code, when Seam can identify it.
             */
            public string|null $unmanaged_access_code_id = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * No space for access code on device.
     */
    final class NoSpaceForAccessCodeOnDevice extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(
            mixed $json,
        ): NoSpaceForAccessCodeOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Code was modified or removed externally after Seam successfully set it on the device. The external change conflicts with the state that Seam is trying to apply, so Seam will attempt to set the code on the device again.
     */
    final class ConflictingExternalModification extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(
            mixed $json,
        ): ConflictingExternalModification|null {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                change_type: $json->change_type ?? null,
                created_at: $json->created_at ?? null,
                modified_fields: \Seam\Parse::to_list(
                    $json->modified_fields ?? null,
                    fn(
                        $m,
                    ) => \Seam\Resources\UnmanagedAccessCode\Errors\ConflictingExternalModification\ModifiedFields::from_json(
                        $m,
                    ),
                ),
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates the type of external modification. `modified` means the code's PIN or schedule was changed. `removed` means the code was deleted from the device.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ConflictingExternalModification\ChangeType>|string|null
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             *
             * @var list<\Seam\Resources\UnmanagedAccessCode\Errors\ConflictingExternalModification\ModifiedFields>|null
             */
            public array|null $modified_fields = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the access code is disabled or inactive on the device. The code exists but will not grant access until re-enabled.
     */
    final class AccessCodeInactive extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): AccessCodeInactive|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * The code cannot be set on the device because it violates the device's code constraints (for example, its length, digits, or a too-simple value). The code will not be retried until you change it. See the device's `code_constraints` and `supported_code_lengths`.
     */
    final class CodeConstraintsViolated extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(
            mixed $json,
        ): CodeConstraintsViolated|null {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Seam was unable to issue this access code before its start time, so the recipient may be unable to unlock the device. This usually points to a problem that needs attention, such as an offline or disconnected device. Seam keeps retrying, and this error clears automatically if the access code is eventually issued.
     */
    final class FailedToIssue extends \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): FailedToIssue|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Seam was unable to apply this access code's requested update to the device, so the code on the device does not match its requested state. Seam keeps retrying, and this error clears automatically once the update is applied.
     */
    final class FailedToUpdate extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): FailedToUpdate|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * This access code is still active on the device even though its `ends_at` has passed, so the recipient may still be able to unlock the device after their access window ended. Seam is attempting to remove it, and this error clears automatically once the access code is no longer active.
     */
    final class FailedToExpire extends
        \Seam\Resources\UnmanagedAccessCode\Errors
    {
        public static function from_json(mixed $json): FailedToExpire|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                message: $json->message ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the account is disconnected.
     */
    final class AccountDisconnected extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the Salto site user limit has been reached.
     */
    final class SaltoKsSubscriptionLimitExceeded extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that Seam's integration user does not have sufficient permissions on the provider's system to which this device belongs, so Seam cannot manage access codes or unlock the device. See the error message for specifics, then either reauthorize the connected account in Seam or grant the integration user the required permissions in the provider's system.
     */
    final class InsufficientPermissions extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that one or more dormakaba sites associated with the connected account could not be connected. Contact dormakaba support.
     */
    final class DormakabaSitesDisconnected extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the device is offline.
     */
    final class DeviceOffline extends \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the device has been removed.
     */
    final class DeviceRemoved extends \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the hub is disconnected.
     */
    final class HubDisconnected extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the device is disconnected.
     */
    final class DeviceDisconnected extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is empty.
     */
    final class EmptyBackupAccessCodePool extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the user is not authorized to use the August lock.
     */
    final class AugustLockNotAuthorized extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that device credentials are missing.
     */
    final class MissingDeviceCredentials extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the auxiliary heat is running.
     */
    final class AuxiliaryHeatRunning extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that a subscription is required to connect.
     */
    final class SubscriptionRequired extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge), for example, if the Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline. See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class BridgeDisconnected extends
        \Seam\Resources\UnmanagedAccessCode\Errors
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
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
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
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    enum ErrorCode: string
    {
        case PROVIDER_ISSUE = "provider_issue";
        case FAILED_TO_SET_ON_DEVICE = "failed_to_set_on_device";
        case FAILED_TO_REMOVE_FROM_DEVICE = "failed_to_remove_from_device";
        case DUPLICATE_CODE_ON_DEVICE = "duplicate_code_on_device";
        case NO_SPACE_FOR_ACCESS_CODE_ON_DEVICE = "no_space_for_access_code_on_device";
        case CONFLICTING_EXTERNAL_MODIFICATION = "conflicting_external_modification";
        case ACCESS_CODE_INACTIVE = "access_code_inactive";
        case CODE_CONSTRAINTS_VIOLATED = "code_constraints_violated";
        case FAILED_TO_ISSUE = "failed_to_issue";
        case FAILED_TO_UPDATE = "failed_to_update";
        case FAILED_TO_EXPIRE = "failed_to_expire";
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

namespace Seam\Resources\UnmanagedAccessCode\Errors\ConflictingExternalModification {
    /**
     * List of fields that were changed externally, with their previous and new values.
     */
    class ModifiedFields
    {
        public static function from_json(mixed $json): ModifiedFields|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                field: $json->field ?? null,
                from: $json->from ?? null,
                to: $json->to ?? null,
            );
        }

        public function __construct(
            /**
             * The name of the field that was changed (e.g. `code`, `starts_at`, `ends_at`).
             */
            public string|null $field,
            /**
             * The previous value of the field.
             */
            public string|null $from,
            /**
             * The new value of the field.
             */
            public string|null $to,
        ) {}
    }

    enum ChangeType: string
    {
        case MODIFIED = "modified";
        case REMOVED = "removed";
    }
}

namespace Seam\Resources\UnmanagedAccessCode\Warnings {
    /**
     * The access code's PIN rotates periodically when the code is renewed. Retrieve the latest code before each use.
     */
    final class CodeRotatesPeriodically extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): CodeRotatesPeriodically|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * The device's time zone is unknown and this code's time frame crosses a daylight-saving transition in at least one plausible time zone. A 1-hour safety buffer has been applied to the side of the time frame affected by the transition (`ends_at` for spring-forward, `starts_at` for fall-back) so the code stays active through the shift — the code may be usable up to 1 hour beyond your requested window. Set the device's time zone via `/devices/report_provider_metadata` to clear the buffer and guarantee exact handling.
     */
    final class TimeFrameAdjustedForUnknownTimeZone extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TimeFrameAdjustedForUnknownTimeZone|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Code was modified or removed externally after Seam successfully set it on the device. External modification is allowed for this code, so the externally modified state is being honored.
     */
    final class ExternalModificationInEffect extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ExternalModificationInEffect|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                change_type: $json->change_type ?? null,
                created_at: $json->created_at ?? null,
                modified_fields: \Seam\Parse::to_list(
                    $json->modified_fields ?? null,
                    fn(
                        $m,
                    ) => \Seam\Resources\UnmanagedAccessCode\Warnings\ExternalModificationInEffect\ModifiedFields::from_json(
                        $m,
                    ),
                ),
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Indicates the type of external modification. `modified` means the code's PIN or schedule was changed. `removed` means the code was deleted from the device.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\ExternalModificationInEffect\ChangeType>|string|null
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             *
             * @var list<\Seam\Resources\UnmanagedAccessCode\Warnings\ExternalModificationInEffect\ModifiedFields>|null
             */
            public array|null $modified_fields = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Delay in setting code on device.
     */
    final class DelayInSettingOnDevice extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DelayInSettingOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Delay in removing code from device.
     */
    final class DelayInRemovingFromDevice extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DelayInRemovingFromDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Seam has not yet issued this access code, even though its start time is approaching, so access may not be ready when the recipient arrives. Seam is still attempting to issue it, and this warning clears automatically once issuance succeeds.
     */
    final class DelayInIssuing extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(mixed $json): DelayInIssuing|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Third-party integration detected that may cause access codes to fail.
     */
    final class ThirdPartyIntegrationDetected extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ThirdPartyIntegrationDetected|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Algopins must be used within 24 hours.
     */
    final class IglooAlgopinMustBeUsedWithin_24Hours extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): IglooAlgopinMustBeUsedWithin_24Hours|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Management was transferred to another workspace.
     */
    final class ManagementTransferred extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ManagementTransferred|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * A backup access code has been pulled and is being used in place of this access code.
     */
    final class UsingBackupAccessCode extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UsingBackupAccessCode|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Access code is being deleted.
     */
    final class BeingDeleted extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * An unknown issue occurred with the access code.
     */
    final class UnknownIssueWithAccessCode extends
        \Seam\Resources\UnmanagedAccessCode\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnknownIssueWithAccessCode|null {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                created_at: $json->created_at ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UnmanagedAccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
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
        case CODE_ROTATES_PERIODICALLY = "code_rotates_periodically";
        case TIME_FRAME_ADJUSTED_FOR_UNKNOWN_TIME_ZONE = "time_frame_adjusted_for_unknown_time_zone";
        case EXTERNAL_MODIFICATION_IN_EFFECT = "external_modification_in_effect";
        case DELAY_IN_SETTING_ON_DEVICE = "delay_in_setting_on_device";
        case DELAY_IN_REMOVING_FROM_DEVICE = "delay_in_removing_from_device";
        case DELAY_IN_ISSUING = "delay_in_issuing";
        case THIRD_PARTY_INTEGRATION_DETECTED = "third_party_integration_detected";
        case IGLOO_ALGOPIN_MUST_BE_USED_WITHIN_24_HOURS = "igloo_algopin_must_be_used_within_24_hours";
        case MANAGEMENT_TRANSFERRED = "management_transferred";
        case USING_BACKUP_ACCESS_CODE = "using_backup_access_code";
        case BEING_DELETED = "being_deleted";
        case UNKNOWN_ISSUE_WITH_ACCESS_CODE = "unknown_issue_with_access_code";
    }
}

namespace Seam\Resources\UnmanagedAccessCode\Warnings\ExternalModificationInEffect {
    /**
     * List of fields that were changed externally, with their previous and new values.
     */
    class ModifiedFields
    {
        public static function from_json(mixed $json): ModifiedFields|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                field: $json->field ?? null,
                from: $json->from ?? null,
                to: $json->to ?? null,
            );
        }

        public function __construct(
            /**
             * The name of the field that was changed (e.g. `code`, `starts_at`, `ends_at`).
             */
            public string|null $field,
            /**
             * The previous value of the field.
             */
            public string|null $from,
            /**
             * The new value of the field.
             */
            public string|null $to,
        ) {}
    }

    enum ChangeType: string
    {
        case MODIFIED = "modified";
        case REMOVED = "removed";
    }
}
