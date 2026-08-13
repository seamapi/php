<?php

namespace Seam\Resources {
    /**
     * Represents an Access Grant. Access Grants enable you to grant a user identity access to spaces, entrances, and devices through one or more access methods, such as mobile keys, plastic cards, and PIN codes. You can create an Access Grant for an existing user identity, or you can create a new user identity *while* creating the new Access Grant.
     */
    class AccessGrant
    {
        public static function from_json(mixed $json): AccessGrant|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                access_grant_key: $json->access_grant_key ?? null,
                access_method_ids: $json->access_method_ids ?? null,
                client_session_token: $json->client_session_token ?? null,
                created_at: $json->created_at ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                display_name: $json->display_name ?? null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => AccessGrant\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                instant_key_url: $json->instant_key_url ?? null,
                location_ids: $json->location_ids ?? null,
                name: $json->name ?? null,
                pending_mutations: array_map(
                    fn($p) => AccessGrant\PendingMutations::from_json($p),
                    $json->pending_mutations ?? [],
                ),
                requested_access_methods: array_map(
                    fn($r) => AccessGrant\RequestedAccessMethods::from_json($r),
                    $json->requested_access_methods ?? [],
                ),
                reservation_key: $json->reservation_key ?? null,
                space_ids: $json->space_ids ?? null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                warnings: array_map(
                    fn($w) => AccessGrant\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Unique key for the access grant within the workspace.
             */
            public string|null $access_grant_key = null,
            /**
             * IDs of the access methods created for the Access Grant.
             */
            public array|null $access_method_ids,
            /**
             * Client Session Token. Only returned if the Access Grant has a mobile_key access method.
             */
            public string|null $client_session_token = null,
            /**
             * Date and time at which the Access Grant was created.
             */
            public string|null $created_at,
            /**
             * ID of the customization profile associated with the Access Grant.
             */
            public string|null $customization_profile_id = null,
            /**
             * Display name of the Access Grant.
             */
            public string|null $display_name,
            /**
             * Date and time at which the Access Grant ends.
             */
            public string|null $ends_at,
            /**
             * Errors associated with the [access grant](https://docs.seam.co/use-cases/granting-access).
             */
            public array $errors,
            /**
             * Instant Key URL. Only returned if the Access Grant has a single mobile_key access_method.
             */
            public string|null $instant_key_url = null,
            /**
             * @deprecated Use `space_ids`.
             */
            public array|null $location_ids,
            /**
             * Name of the Access Grant. If not provided, the display name will be computed.
             */
            public string|null $name,
            /**
             * List of pending mutations for the access grant. This shows updates that are in progress.
             */
            public array $pending_mutations,
            /**
             * Access methods that the user requested for the Access Grant.
             */
            public array $requested_access_methods,
            /**
             * Reservation key for the access grant.
             */
            public string|null $reservation_key = null,
            /**
             * IDs of the spaces to which the Access Grant gives access.
             */
            public array|null $space_ids,
            /**
             * Date and time at which the Access Grant starts.
             */
            public string|null $starts_at,
            /**
             * ID of user identity to which the Access Grant gives access.
             */
            public string|null $user_identity_id,
            /**
             * Warnings associated with the [access grant](https://docs.seam.co/use-cases/granting-access).
             */
            public array $warnings,
            /**
             * ID of the Seam workspace associated with the Access Grant.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant {
    /**
     * Errors associated with the [access grant](https://docs.seam.co/use-cases/granting-access).
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
                message: $json->message ?? null,
                missing_device_ids: $json->missing_device_ids ?? null,
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
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * IDs of the devices that did not receive an access code at grant creation. Use these to identify which specific devices failed when the message reports a partial failure.
             */
            public array|null $missing_device_ids = null,
        ) {}
    }

    /**
     * List of pending mutations for the access grant. This shows updates that are in progress.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_ids: $json->access_method_ids ?? null,
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? PendingMutations\From::from_json($json->from)
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? PendingMutations\To::from_json($json->to)
                    : null,
            );
        }

        public function __construct(
            /**
             * IDs of the access methods being updated.
             */
            public array|null $access_method_ids,
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
            public PendingMutations\To|null $to,
        ) {}
    }

    /**
     * Access methods that the user requested for the Access Grant.
     */
    class RequestedAccessMethods
    {
        public static function from_json(
            mixed $json,
        ): RequestedAccessMethods|null {
            if (!$json) {
                return null;
            }
            return new self(
                code: $json->code ?? null,
                created_access_method_ids: $json->created_access_method_ids ??
                    null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                instant_key_max_use_count: $json->instant_key_max_use_count ??
                    null,
                mode: $json->mode ?? null,
            );
        }

        public function __construct(
            /**
             * Specific PIN code to use for this access method. Only applicable when mode is 'code'.
             */
            public string|null $code = null,
            /**
             * IDs of the access methods created for the requested access method.
             */
            public array|null $created_access_method_ids,
            /**
             * Date and time at which the requested access method was added to the Access Grant.
             */
            public string|null $created_at,
            /**
             * Display name of the access method.
             */
            public string|null $display_name,
            /**
             * Maximum number of times the instant key can be used. Only applicable when mode is 'mobile_key'. Defaults to 1 if not specified.
             */
            public int|null $instant_key_max_use_count = null,
            /**
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public string|null $mode,
        ) {}
    }

    /**
     * Warnings associated with the [access grant](https://docs.seam.co/use-cases/granting-access).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_ids: $json->access_method_ids ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                failed_devices: array_map(
                    fn($f) => Warnings\FailedDevices::from_json($f),
                    $json->failed_devices ?? [],
                ),
                message: $json->message ?? null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
                reason: $json->reason ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access methods being updated.
             */
            public array|null $access_method_ids,
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            public string|null $device_id,
            /**
             * Devices whose access codes could not be revoked during reconciliation. Present when the provider does not support revoking an offline access code (e.g. Dormakaba oracode with exhausted override budget).
             */
            public array|null $failed_devices = null,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * The new PIN code that was assigned instead.
             */
            public string|null $new_code,
            /**
             * The originally requested PIN code that was unavailable.
             */
            public string|null $original_code,
            /**
             * Specific reason why the grant's times are not programmable on the device.
             */
            public string|null $reason,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant\PendingMutations {
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_ids: $json->device_ids ?? null,
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Previous device IDs where access codes existed.
             */
            public array|null $device_ids,
            /**
             * Previous end time for access.
             */
            public string|null $ends_at,
            /**
             * Previous start time for access.
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
                common_code_key: $json->common_code_key ?? null,
                device_ids: $json->device_ids ?? null,
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Common code key to ensure PIN code reuse across devices.
             */
            public string|null $common_code_key = null,
            /**
             * New device IDs where access codes should be created.
             */
            public array|null $device_ids,
            /**
             * New end time for access.
             */
            public string|null $ends_at,
            /**
             * New start time for access.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant\Warnings {
    /**
     * Devices whose access codes could not be revoked during reconciliation. Present when the provider does not support revoking an offline access code (e.g. Dormakaba oracode with exhausted override budget).
     */
    class FailedDevices
    {
        public static function from_json(mixed $json): FailedDevices|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_id: $json->device_id ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Device whose access code could not be revoked.
             */
            public string|null $device_id,
            /**
             * Reason the access code could not be revoked (e.g. `offline_access_code_not_revocable`).
             */
            public string|null $error_code,
            /**
             * Human-readable description of why revocation failed.
             */
            public string|null $message,
        ) {}
    }
}
