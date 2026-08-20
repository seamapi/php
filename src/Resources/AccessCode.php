<?php

namespace Seam\Resources {
    /**
     * Represents a smart lock [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * An access code is a code used for a keypad or pinpad device. Unlike physical keys, which can easily be lost or duplicated, PIN codes can be customized, tracked, and altered on the fly. Using the Seam Access Code API, you can easily generate access codes on the hundreds of door lock models with which we integrate.
     *
     * Seam supports programming two types of access codes: [ongoing](https://docs.seam.co/low-level-apis/smart-locks/access-codes#ongoing-access-codes) and [time-bound](https://docs.seam.co/low-level-apis/smart-locks/access-codes#time-bound-access-codes). To differentiate between the two, refer to the `type` property of the access code. Ongoing codes display as `ongoing`, whereas time-bound codes are labeled `time_bound`. An ongoing access code is active, until it has been removed from the device. To specify an ongoing access code, leave both `starts_at` and `ends_at` empty. A time-bound access code will be programmed at the `starts_at` time and removed at the `ends_at` time.
     *
     * In addition, for certain devices, Seam also supports [offline access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes#offline-access-codes). Offline access (PIN) codes are designed for door locks that might not always maintain an internet connection. For this type of access code, the device manufacturer uses encryption keys (tokens) to create server-based registries of algorithmically-generated offline PIN codes. Because the tokens remain synchronized with the managed devices, the locks do not require an active internet connection—and you do not need to be near the locks—to create an offline access code. Then, owners or managers can share these offline codes with users through a variety of mechanisms, such as messaging applications. That is, lock users do not need to install a smartphone application to receive an offline access code.
     *
     * For granting a person access to a space, [Access Grants](https://docs.seam.co/use-cases/granting-access) are the default and recommended approach and work across both standalone smart locks and access systems. Use the lower-level Access Codes API directly only when you specifically need to manage individual PIN codes.
     */
    class AccessCode
    {
        public static function from_json(mixed $json): AccessCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                code: $json->code ?? null,
                common_code_key: $json->common_code_key ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AccessCode\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_backup_access_code_available: $json->is_backup_access_code_available ??
                    null,
                is_external_modification_allowed: $json->is_external_modification_allowed ??
                    null,
                is_managed: $json->is_managed ?? null,
                is_offline_access_code: $json->is_offline_access_code ?? null,
                is_one_time_use: $json->is_one_time_use ?? null,
                name: $json->name ?? null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\AccessCode\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                status: $json->status ?? null,
                type: $json->type ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\AccessCode\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                dormakaba_oracode_metadata: isset(
                    $json->dormakaba_oracode_metadata,
                )
                    ? \Seam\Resources\AccessCode\DormakabaOracodeMetadata::from_json(
                        $json->dormakaba_oracode_metadata,
                    )
                    : null,
                ends_at: $json->ends_at ?? null,
                is_backup: $json->is_backup ?? null,
                is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
                is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ??
                    null,
                pulled_backup_access_code_id: $json->pulled_backup_access_code_id ??
                    null,
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
             * Unique identifier for a group of access codes that share the same code.
             */
            public string|null $common_code_key,
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
             * @var list<\Seam\Resources\AccessCode\Errors>
             */
            public array $errors,
            /**
             * Indicates whether a backup access code is available for use if the primary access code is lost or compromised.
             */
            public bool|null $is_backup_access_code_available,
            /**
             * Indicates whether changes to the access code from external sources are permitted.
             */
            public bool|null $is_external_modification_allowed,
            /**
             * Indicates whether Seam manages the access code.
             */
            public true|null $is_managed,
            /**
             * Indicates whether the access code is intended for use in offline scenarios. If `true`, this code can be created on a device without a network connection.
             */
            public bool|null $is_offline_access_code,
            /**
             * Indicates whether the access code can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use,
            /**
             * Name of the access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes. Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`. To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints. To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
             */
            public string|null $name,
            /**
             * Collection of pending mutations for the access code. Indicates changes that Seam is in the process of pushing to the device.
             *
             * @var list<\Seam\Resources\AccessCode\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Current status of the access code within the operational lifecycle. Values are `setting`, a transitional phase that indicates that the code is being configured or activated; `set`, which indicates that the code is active and operational; `unset`, which indicates a deactivated or unused state, either before activation or after deliberate deactivation; `removing`, which indicates a transitional period in which the code is being deleted or made inactive; and `unknown`, which indicates an indeterminate state, due to reasons such as system errors or incomplete data, that highlights a potential need for system review or troubleshooting. See also [Lifecycle of Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/lifecycle-of-access-codes).
             *
             * @var value-of<\Seam\Resources\AccessCode\Status>|string|null
             */
            public string|null $status,
            /**
             * Type of the access code. `ongoing` access codes are active continuously until deactivated manually. `time_bound` access codes have a specific duration.
             *
             * @var value-of<\Seam\Resources\AccessCode\Type>|string|null
             */
            public string|null $type,
            /**
             * Warnings associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
             *
             * @var list<\Seam\Resources\AccessCode\Warnings>
             */
            public array $warnings,
            /**
             * Unique identifier for the Seam workspace associated with the access code.
             */
            public string|null $workspace_id,
            /**
             * Metadata for a dormakaba Oracode managed access code. Only present for access codes from dormakaba Oracode devices.
             */
            public \Seam\Resources\AccessCode\DormakabaOracodeMetadata|null $dormakaba_oracode_metadata = null,
            /**
             * Date and time after which the time-bound access code becomes inactive.
             */
            public string|null $ends_at = null,
            /**
             * Indicates whether the access code is a backup code.
             */
            public bool|null $is_backup = null,
            /**
             * Indicates whether the code is set on the device according to a preconfigured schedule.
             */
            public bool|null $is_scheduled_on_device = null,
            /**
             * Indicates whether the access code is waiting for a code assignment.
             */
            public bool|null $is_waiting_for_code_assignment = null,
            /**
             * Identifier of the pulled backup access code. Used to associate the pulled backup access code with the original access code.
             */
            public string|null $pulled_backup_access_code_id = null,
            /**
             * Date and time at which the time-bound access code becomes active.
             */
            public string|null $starts_at = null,
        ) {}
    }
}

namespace Seam\Resources\AccessCode {
    /**
     * Metadata for a dormakaba Oracode managed access code. Only present for access codes from dormakaba Oracode devices.
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
                ? \Seam\Resources\AccessCode\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessCode\Errors\ErrorCode::PROVIDER_ISSUE
                    => \Seam\Resources\AccessCode\Errors\ProviderIssue::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::FAILED_TO_SET_ON_DEVICE
                    => \Seam\Resources\AccessCode\Errors\FailedToSetOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::FAILED_TO_REMOVE_FROM_DEVICE
                    => \Seam\Resources\AccessCode\Errors\FailedToRemoveFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::DUPLICATE_CODE_ON_DEVICE
                    => \Seam\Resources\AccessCode\Errors\DuplicateCodeOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::NO_SPACE_FOR_ACCESS_CODE_ON_DEVICE
                    => \Seam\Resources\AccessCode\Errors\NoSpaceForAccessCodeOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::CONFLICTING_EXTERNAL_MODIFICATION
                    => \Seam\Resources\AccessCode\Errors\ConflictingExternalModification::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::ACCESS_CODE_INACTIVE
                    => \Seam\Resources\AccessCode\Errors\AccessCodeInactive::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\AccessCode\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\AccessCode\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::INSUFFICIENT_PERMISSIONS
                    => \Seam\Resources\AccessCode\Errors\InsufficientPermissions::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::DORMAKABA_SITES_DISCONNECTED
                    => \Seam\Resources\AccessCode\Errors\DormakabaSitesDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::DEVICE_OFFLINE
                    => \Seam\Resources\AccessCode\Errors\DeviceOffline::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::DEVICE_REMOVED
                    => \Seam\Resources\AccessCode\Errors\DeviceRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::HUB_DISCONNECTED
                    => \Seam\Resources\AccessCode\Errors\HubDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::DEVICE_DISCONNECTED
                    => \Seam\Resources\AccessCode\Errors\DeviceDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::EMPTY_BACKUP_ACCESS_CODE_POOL
                    => \Seam\Resources\AccessCode\Errors\EmptyBackupAccessCodePool::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::AUGUST_LOCK_NOT_AUTHORIZED
                    => \Seam\Resources\AccessCode\Errors\AugustLockNotAuthorized::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::MISSING_DEVICE_CREDENTIALS
                    => \Seam\Resources\AccessCode\Errors\MissingDeviceCredentials::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::AUXILIARY_HEAT_RUNNING
                    => \Seam\Resources\AccessCode\Errors\AuxiliaryHeatRunning::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::SUBSCRIPTION_REQUIRED
                    => \Seam\Resources\AccessCode\Errors\SubscriptionRequired::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\AccessCode\Errors\BridgeDisconnected::from_json(
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Collection of pending mutations for the access code. Indicates changes that Seam is in the process of pushing to the device. Known mutation_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->mutation_code ?? null)
                ? \Seam\Resources\AccessCode\PendingMutations\MutationCode::tryFrom(
                    $json->mutation_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::CREATING
                    => \Seam\Resources\AccessCode\PendingMutations\Creating::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::DEFERRING_CREATION
                    => \Seam\Resources\AccessCode\PendingMutations\DeferringCreation::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::DELETING
                    => \Seam\Resources\AccessCode\PendingMutations\Deleting::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::UPDATING_CODE
                    => \Seam\Resources\AccessCode\PendingMutations\UpdatingCode::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::UPDATING_NAME
                    => \Seam\Resources\AccessCode\PendingMutations\UpdatingName::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\PendingMutations\MutationCode::UPDATING_TIME_FRAME
                    => \Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    mutation_code: $json->mutation_code ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            public string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            public string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            public string|null $mutation_code,
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
                ? \Seam\Resources\AccessCode\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessCode\Warnings\WarningCode::CODE_ROTATES_PERIODICALLY
                    => \Seam\Resources\AccessCode\Warnings\CodeRotatesPeriodically::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::TIME_FRAME_ADJUSTED_FOR_UNKNOWN_TIME_ZONE
                    => \Seam\Resources\AccessCode\Warnings\TimeFrameAdjustedForUnknownTimeZone::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::EXTERNAL_MODIFICATION_IN_EFFECT
                    => \Seam\Resources\AccessCode\Warnings\ExternalModificationInEffect::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::DELAY_IN_SETTING_ON_DEVICE
                    => \Seam\Resources\AccessCode\Warnings\DelayInSettingOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::DELAY_IN_REMOVING_FROM_DEVICE
                    => \Seam\Resources\AccessCode\Warnings\DelayInRemovingFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::THIRD_PARTY_INTEGRATION_DETECTED
                    => \Seam\Resources\AccessCode\Warnings\ThirdPartyIntegrationDetected::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::IGLOO_ALGOPIN_MUST_BE_USED_WITHIN_24_HOURS
                    => \Seam\Resources\AccessCode\Warnings\IglooAlgopinMustBeUsedWithin_24Hours::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::MANAGEMENT_TRANSFERRED
                    => \Seam\Resources\AccessCode\Warnings\ManagementTransferred::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::USING_BACKUP_ACCESS_CODE
                    => \Seam\Resources\AccessCode\Warnings\UsingBackupAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\AccessCode\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\AccessCode\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_ACCESS_CODE
                    => \Seam\Resources\AccessCode\Warnings\UnknownIssueWithAccessCode::from_json(
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        case SETTING = "setting";
        case SET = "set";
        case VALUE_UNSET = "unset";
        case REMOVING = "removing";
        case UNKNOWN = "unknown";
    }

    enum Type: string
    {
        case TIME_BOUND = "time_bound";
        case ONGOING = "ongoing";
    }
}

namespace Seam\Resources\AccessCode\Errors {
    /**
     * Indicates a provider-specific issue that prevents the access code from being set or managed. Check the error message for details.
     */
    final class ProviderIssue extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class FailedToSetOnDevice extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class DuplicateCodeOnDevice extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
                modified_fields: array_map(
                    fn(
                        $m,
                    ) => \Seam\Resources\AccessCode\Errors\ConflictingExternalModification\ModifiedFields::from_json(
                        $m,
                    ),
                    $json->modified_fields ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ConflictingExternalModification\ChangeType>|string|null
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             *
             * @var list<\Seam\Resources\AccessCode\Errors\ConflictingExternalModification\ModifiedFields>|null
             */
            public array|null $modified_fields = null,
        ) {
            parent::__construct(error_code: $error_code, message: $message);
        }
    }

    /**
     * Indicates that the access code is disabled or inactive on the device. The code exists but will not grant access until re-enabled.
     */
    final class AccessCodeInactive extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class AccountDisconnected extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class DeviceOffline extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class DeviceRemoved extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class HubDisconnected extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class DeviceDisconnected extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
        \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class AuxiliaryHeatRunning extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class SubscriptionRequired extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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
    final class BridgeDisconnected extends \Seam\Resources\AccessCode\Errors
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
             * @var value-of<\Seam\Resources\AccessCode\Errors\ErrorCode>|string|null
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

namespace Seam\Resources\AccessCode\Errors\ConflictingExternalModification {
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

namespace Seam\Resources\AccessCode\PendingMutations {
    /**
     * Seam is in the process of setting an access code on the device.
     */
    final class Creating extends \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): Creating|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is waiting until closer to the access code's start time before programming it on the device.
     */
    final class DeferringCreation extends
        \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): DeferringCreation|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                scheduled_at: $json->scheduled_at ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * Date and time at which Seam will attempt to program this access code on the device.
             */
            public string|null $scheduled_at,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of removing an access code from the device.
     */
    final class Deleting extends \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): Deleting|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an updated PIN code to the device.
     */
    final class UpdatingCode extends \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingCode\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingCode\To::from_json(
                        $json->to,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Previous code configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingCode\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * New code configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingCode\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an updated access code name to the device.
     */
    final class UpdatingName extends \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingName|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingName\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingName\To::from_json(
                        $json->to,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Previous name configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingName\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * New name configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingName\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an updated time frame to the device.
     */
    final class UpdatingTimeFrame extends
        \Seam\Resources\AccessCode\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingTimeFrame|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame\To::from_json(
                        $json->to,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Previous time frame configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of setting an access code on the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * New time frame configuration.
             */
            public \Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    enum MutationCode: string
    {
        case CREATING = "creating";
        case DEFERRING_CREATION = "deferring_creation";
        case DELETING = "deleting";
        case UPDATING_CODE = "updating_code";
        case UPDATING_NAME = "updating_name";
        case UPDATING_TIME_FRAME = "updating_time_frame";
    }
}

namespace Seam\Resources\AccessCode\PendingMutations\UpdatingCode {
    /**
     * Previous code configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(code: $json->code ?? null);
        }

        public function __construct(
            /**
             * Previous PIN code.
             */
            public string|null $code,
        ) {}
    }

    /**
     * New code configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(code: $json->code ?? null);
        }

        public function __construct(
            /**
             * New PIN code.
             */
            public string|null $code,
        ) {}
    }
}

namespace Seam\Resources\AccessCode\PendingMutations\UpdatingName {
    /**
     * Previous name configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(name: $json->name ?? null);
        }

        public function __construct(
            /**
             * Previous access code name.
             */
            public string|null $name,
        ) {}
    }

    /**
     * New name configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(name: $json->name ?? null);
        }

        public function __construct(
            /**
             * New access code name.
             */
            public string|null $name,
        ) {}
    }
}

namespace Seam\Resources\AccessCode\PendingMutations\UpdatingTimeFrame {
    /**
     * Previous time frame configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Previous end time for the access code.
             */
            public string|null $ends_at,
            /**
             * Previous start time for the access code.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * New time frame configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * New end time for the access code.
             */
            public string|null $ends_at,
            /**
             * New start time for the access code.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\AccessCode\Warnings {
    /**
     * The access code's PIN rotates periodically when the code is renewed. Retrieve the latest code before each use.
     */
    final class CodeRotatesPeriodically extends
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
                modified_fields: array_map(
                    fn(
                        $m,
                    ) => \Seam\Resources\AccessCode\Warnings\ExternalModificationInEffect\ModifiedFields::from_json(
                        $m,
                    ),
                    $json->modified_fields ?? [],
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Indicates the type of external modification. `modified` means the code's PIN or schedule was changed. `removed` means the code was deleted from the device.
             *
             * @var value-of<\Seam\Resources\AccessCode\Warnings\ExternalModificationInEffect\ChangeType>|string|null
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             *
             * @var list<\Seam\Resources\AccessCode\Warnings\ExternalModificationInEffect\ModifiedFields>|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
    final class BeingDeleted extends \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        \Seam\Resources\AccessCode\Warnings
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
             * @var value-of<\Seam\Resources\AccessCode\Warnings\WarningCode>|string|null
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
        case THIRD_PARTY_INTEGRATION_DETECTED = "third_party_integration_detected";
        case IGLOO_ALGOPIN_MUST_BE_USED_WITHIN_24_HOURS = "igloo_algopin_must_be_used_within_24_hours";
        case MANAGEMENT_TRANSFERRED = "management_transferred";
        case USING_BACKUP_ACCESS_CODE = "using_backup_access_code";
        case BEING_DELETED = "being_deleted";
        case UNKNOWN_ISSUE_WITH_ACCESS_CODE = "unknown_issue_with_access_code";
    }
}

namespace Seam\Resources\AccessCode\Warnings\ExternalModificationInEffect {
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
