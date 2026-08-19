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
                dormakaba_oracode_metadata: isset(
                    $json->dormakaba_oracode_metadata,
                )
                    ? AccessCode\DormakabaOracodeMetadata::from_json(
                        $json->dormakaba_oracode_metadata,
                    )
                    : null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => AccessCode\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_backup: $json->is_backup ?? null,
                is_backup_access_code_available: $json->is_backup_access_code_available ??
                    null,
                is_external_modification_allowed: $json->is_external_modification_allowed ??
                    null,
                is_managed: $json->is_managed ?? null,
                is_offline_access_code: $json->is_offline_access_code ?? null,
                is_one_time_use: $json->is_one_time_use ?? null,
                is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
                is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ??
                    null,
                name: $json->name ?? null,
                pending_mutations: array_map(
                    fn($p) => AccessCode\PendingMutations::from_json($p),
                    $json->pending_mutations ?? [],
                ),
                pulled_backup_access_code_id: $json->pulled_backup_access_code_id ??
                    null,
                starts_at: $json->starts_at ?? null,
                status: $json->status ?? null,
                type: $json->type ?? null,
                warnings: array_map(
                    fn($w) => AccessCode\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
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
             */
            public array $pending_mutations,
            /**
             * Current status of the access code within the operational lifecycle. Values are `setting`, a transitional phase that indicates that the code is being configured or activated; `set`, which indicates that the code is active and operational; `unset`, which indicates a deactivated or unused state, either before activation or after deliberate deactivation; `removing`, which indicates a transitional period in which the code is being deleted or made inactive; and `unknown`, which indicates an indeterminate state, due to reasons such as system errors or incomplete data, that highlights a potential need for system review or troubleshooting. See also [Lifecycle of Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/lifecycle-of-access-codes).
             */
            public string|null $status,
            /**
             * Type of the access code. `ongoing` access codes are active continuously until deactivated manually. `time_bound` access codes have a specific duration.
             */
            public string|null $type,
            /**
             * Warnings associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
             */
            public array $warnings,
            /**
             * Unique identifier for the Seam workspace associated with the access code.
             */
            public string|null $workspace_id,
            /**
             * Metadata for a dormakaba Oracode managed access code. Only present for access codes from dormakaba Oracode devices.
             */
            public AccessCode\DormakabaOracodeMetadata|null $dormakaba_oracode_metadata = null,
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
     * Errors associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                change_type: $json->change_type ?? null,
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                is_access_code_error: $json->is_access_code_error ?? null,
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
                is_device_error: $json->is_device_error ?? null,
                managed_access_code_id: $json->managed_access_code_id ?? null,
                message: $json->message ?? null,
                modified_fields: array_map(
                    fn($m) => Errors\ModifiedFields::from_json($m),
                    $json->modified_fields ?? [],
                ),
                unmanaged_access_code_id: $json->unmanaged_access_code_id ??
                    null,
            );
        }

        public function __construct(
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public string|null $error_code,
            /**
             * Indicates that this is an access code error.
             */
            public true|null $is_access_code_error,
            public bool|null $is_connected_account_error,
            public bool|null $is_device_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Indicates the type of external modification. `modified` means the code's PIN or schedule was changed. `removed` means the code was deleted from the device.
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at = null,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error = null,
            /**
             * ID of the managed access code that conflicts with this managed access code, when Seam can identify it.
             */
            public string|null $managed_access_code_id = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             */
            public array|null $modified_fields = null,
            /**
             * ID of the unmanaged access code that conflicts with this managed access code, when Seam can identify it.
             */
            public string|null $unmanaged_access_code_id = null,
        ) {}
    }

    /**
     * Collection of pending mutations for the access code. Indicates changes that Seam is in the process of pushing to the device.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? PendingMutations\From::from_json($json->from)
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                scheduled_at: $json->scheduled_at ?? null,
                to: isset($json->to)
                    ? PendingMutations\To::from_json($json->to)
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            public string|null $created_at,
            public PendingMutations\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            public string|null $message,
            public string|null $mutation_code,
            /**
             * Date and time at which Seam will attempt to program this access code on the device.
             */
            public string|null $scheduled_at,
            public PendingMutations\To|null $to,
        ) {}
    }

    /**
     * Warnings associated with the [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                change_type: $json->change_type ?? null,
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                modified_fields: array_map(
                    fn($m) => Warnings\ModifiedFields::from_json($m),
                    $json->modified_fields ?? [],
                ),
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
            /**
             * Indicates the type of external modification. `modified` means the code's PIN or schedule was changed. `removed` means the code was deleted from the device.
             */
            public string|null $change_type = null,
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at = null,
            /**
             * List of fields that were changed externally, with their previous and new values.
             */
            public array|null $modified_fields = null,
        ) {}
    }
}

namespace Seam\Resources\AccessCode\Errors {
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
}

namespace Seam\Resources\AccessCode\PendingMutations {
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Previous PIN code.
             */
            public string|null $code,
            /**
             * Previous end time for the access code.
             */
            public string|null $ends_at,
            /**
             * Previous access code name.
             */
            public string|null $name,
            /**
             * Previous start time for the access code.
             */
            public string|null $starts_at,
        ) {}
    }

    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * New PIN code.
             */
            public string|null $code,
            /**
             * New end time for the access code.
             */
            public string|null $ends_at,
            /**
             * New access code name.
             */
            public string|null $name,
            /**
             * New start time for the access code.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\AccessCode\Warnings {
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
}
