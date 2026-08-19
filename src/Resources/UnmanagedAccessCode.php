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
                cannot_be_managed: $json->cannot_be_managed ?? null,
                cannot_delete_unmanaged_access_code: $json->cannot_delete_unmanaged_access_code ??
                    null,
                code: $json->code ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                dormakaba_oracode_metadata: isset(
                    $json->dormakaba_oracode_metadata,
                )
                    ? UnmanagedAccessCode\DormakabaOracodeMetadata::from_json(
                        $json->dormakaba_oracode_metadata,
                    )
                    : null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => UnmanagedAccessCode\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
                status: $json->status ?? null,
                type: $json->type ?? null,
                warnings: array_map(
                    fn($w) => UnmanagedAccessCode\Warnings::from_json($w),
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
             * Indicates that Seam does not manage the access code.
             */
            public false|null $is_managed,
            /**
             * Name of the access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes. Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`. To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints. To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
             */
            public string|null $name,
            /**
             * Current status of the access code within the operational lifecycle. `set` indicates that the code is active and operational. `unset` indicates that the code exists on the provider but is not usable on the device.
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
            public UnmanagedAccessCode\DormakabaOracodeMetadata|null $dormakaba_oracode_metadata = null,
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

namespace Seam\Resources\UnmanagedAccessCode\Errors {
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

namespace Seam\Resources\UnmanagedAccessCode\Warnings {
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
