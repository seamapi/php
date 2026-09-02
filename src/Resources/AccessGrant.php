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
                access_method_ids: $json->access_method_ids ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                display_status: $json->display_status ?? null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AccessGrant\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                location_ids: $json->location_ids ?? null,
                name: $json->name ?? null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\AccessGrant\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                requested_access_methods: array_map(
                    fn(
                        $r,
                    ) => \Seam\Resources\AccessGrant\RequestedAccessMethods::from_json(
                        $r,
                    ),
                    $json->requested_access_methods ?? [],
                ),
                space_ids: $json->space_ids ?? null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\AccessGrant\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                access_grant_key: $json->access_grant_key ?? null,
                client_session_token: $json->client_session_token ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                instant_key_url: $json->instant_key_url ?? null,
                reservation_key: $json->reservation_key ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * IDs of the access methods created for the Access Grant.
             *
             * @var list<string>|null
             */
            public array|null $access_method_ids,
            /**
             * Date and time at which the Access Grant was created.
             */
            public string|null $created_at,
            /**
             * Display name of the Access Grant.
             */
            public string|null $display_name,
            /**
             * Human-readable sentence answering whether the user can currently get in, for example `Active`, `Awaiting issuance`, or `Upcoming`. For display only. The wording is not stable and is not an enumeration — it may change at any time, so never compare against or branch on it. To make decisions, read `starts_at`, `ends_at`, `errors`, and the access methods' own fields.
             */
            public string|null $display_status,
            /**
             * Date and time at which the Access Grant ends.
             */
            public string|null $ends_at,
            /**
             * Errors associated with the [access grant](https://docs.seam.co/use-cases/granting-access).
             *
             * @var list<\Seam\Resources\AccessGrant\Errors>
             */
            public array $errors,
            /**
             * @var list<string>|null
             * @deprecated Use `space_ids`.
             */
            public array|null $location_ids,
            /**
             * Name of the Access Grant. If not provided, the display name will be computed.
             */
            public string|null $name,
            /**
             * List of pending mutations for the access grant. This shows updates that are in progress.
             *
             * @var list<\Seam\Resources\AccessGrant\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Access methods that the user requested for the Access Grant.
             *
             * @var list<\Seam\Resources\AccessGrant\RequestedAccessMethods>
             */
            public array $requested_access_methods,
            /**
             * IDs of the spaces to which the Access Grant gives access.
             *
             * @var list<string>|null
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
             *
             * @var list<\Seam\Resources\AccessGrant\Warnings>
             */
            public array $warnings,
            /**
             * ID of the Seam workspace associated with the Access Grant.
             */
            public string|null $workspace_id,
            /**
             * Unique key for the access grant within the workspace.
             */
            public string|null $access_grant_key = null,
            /**
             * Client Session Token. Only returned if the Access Grant has a mobile_key access method.
             */
            public string|null $client_session_token = null,
            /**
             * ID of the customization profile associated with the Access Grant.
             */
            public string|null $customization_profile_id = null,
            /**
             * Instant Key URL. Only returned if the Access Grant has a single mobile_key access_method.
             */
            public string|null $instant_key_url = null,
            /**
             * Reservation key for the access grant.
             */
            public string|null $reservation_key = null,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant {
    /**
     * Errors associated with the [access grant](https://docs.seam.co/use-cases/granting-access). Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\AccessGrant\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessGrant\Errors\ErrorCode::CANNOT_CREATE_REQUESTED_ACCESS_METHODS
                    => \Seam\Resources\AccessGrant\Errors\CannotCreateRequestedAccessMethods::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    error_code: $json->error_code ?? null,
                    message: $json->message ?? null,
                    missing_device_ids: $json->missing_device_ids ?? null,
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
             * @var value-of<\Seam\Resources\AccessGrant\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * IDs of the devices that did not receive an access code at grant creation. Use these to identify which specific devices failed when the message reports a partial failure.
             *
             * @var list<string>|null
             */
            public array|null $missing_device_ids = null,
        ) {}
    }

    /**
     * List of pending mutations for the access grant. This shows updates that are in progress. Known mutation_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->mutation_code ?? null)
                ? \Seam\Resources\AccessGrant\PendingMutations\MutationCode::tryFrom(
                    $json->mutation_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessGrant\PendingMutations\MutationCode::UPDATING_SPACES
                    => \Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\PendingMutations\MutationCode::UPDATING_ACCESS_TIMES
                    => \Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes::from_json(
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
             * Mutation code to indicate that Seam is in the process of updating the spaces (devices) associated with this access grant.
             *
             * @var value-of<\Seam\Resources\AccessGrant\PendingMutations\MutationCode>|string|null
             */
            public string|null $mutation_code,
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
                created_access_method_ids: $json->created_access_method_ids ??
                    null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                mode: $json->mode ?? null,
                code: $json->code ?? null,
                instant_key_max_use_count: $json->instant_key_max_use_count ??
                    null,
            );
        }

        public function __construct(
            /**
             * IDs of the access methods created for the requested access method.
             *
             * @var list<string>|null
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
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             *
             * @var value-of<\Seam\Resources\AccessGrant\RequestedAccessMethods\Mode>|string|null
             */
            public string|null $mode,
            /**
             * Specific PIN code to use for this access method. Only applicable when mode is 'code'.
             */
            public string|null $code = null,
            /**
             * Maximum number of times the instant key can be used. Only applicable when mode is 'mobile_key'. Defaults to 1 if not specified.
             */
            public int|null $instant_key_max_use_count = null,
        ) {}
    }

    /**
     * Warnings associated with the [access grant](https://docs.seam.co/use-cases/granting-access). Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\AccessGrant\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessGrant\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\AccessGrant\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::UNDERPROVISIONED_ACCESS
                    => \Seam\Resources\AccessGrant\Warnings\UnderprovisionedAccess::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::OVERPROVISIONED_ACCESS
                    => \Seam\Resources\AccessGrant\Warnings\OverprovisionedAccess::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::UPDATING_ACCESS_TIMES
                    => \Seam\Resources\AccessGrant\Warnings\UpdatingAccessTimes::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::REQUESTED_CODE_UNAVAILABLE
                    => \Seam\Resources\AccessGrant\Warnings\RequestedCodeUnavailable::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::DEVICE_DOES_NOT_SUPPORT_ACCESS_CODES
                    => \Seam\Resources\AccessGrant\Warnings\DeviceDoesNotSupportAccessCodes::from_json(
                    $json,
                ),
                \Seam\Resources\AccessGrant\Warnings\WarningCode::DEVICE_TIME_CONSTRAINTS_VIOLATED
                    => \Seam\Resources\AccessGrant\Warnings\DeviceTimeConstraintsViolated::from_json(
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
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant\Errors {
    /**
     * Indicates that Seam could not create one or more of the requested access methods for the access grant.
     */
    final class CannotCreateRequestedAccessMethods extends
        \Seam\Resources\AccessGrant\Errors
    {
        public static function from_json(
            mixed $json,
        ): CannotCreateRequestedAccessMethods|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AccessGrant\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * IDs of the devices that did not receive an access code at grant creation. Use these to identify which specific devices failed when the message reports a partial failure.
             *
             * @var list<string>|null
             */
            array|null $missing_device_ids = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
                missing_device_ids: $missing_device_ids,
            );
        }
    }

    enum ErrorCode: string
    {
        case CANNOT_CREATE_REQUESTED_ACCESS_METHODS = "cannot_create_requested_access_methods";
    }
}

namespace Seam\Resources\AccessGrant\PendingMutations {
    /**
     * Seam is in the process of updating the devices/spaces associated with this access grant.
     */
    final class UpdatingSpaces extends
        \Seam\Resources\AccessGrant\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingSpaces|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces\To::from_json(
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
             * Previous location configuration.
             */
            public \Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of updating the spaces (devices) associated with this access grant.
             *
             * @var value-of<\Seam\Resources\AccessGrant\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * New location configuration.
             */
            public \Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of updating the access times for this access grant.
     */
    final class UpdatingAccessTimes extends
        \Seam\Resources\AccessGrant\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingAccessTimes|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_ids: $json->access_method_ids ?? null,
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes\To::from_json(
                        $json->to,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * IDs of the access methods being updated.
             *
             * @var list<string>|null
             */
            public array|null $access_method_ids,
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Previous access time configuration.
             */
            public \Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of updating the spaces (devices) associated with this access grant.
             *
             * @var value-of<\Seam\Resources\AccessGrant\PendingMutations\MutationCode>|string|null
             */
            string|null $mutation_code,
            /**
             * New access time configuration.
             */
            public \Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes\To|null $to,
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
        case UPDATING_SPACES = "updating_spaces";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
    }
}

namespace Seam\Resources\AccessGrant\PendingMutations\UpdatingSpaces {
    /**
     * Previous location configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(device_ids: $json->device_ids ?? null);
        }

        public function __construct(
            /**
             * Previous device IDs where access codes existed.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
        ) {}
    }

    /**
     * New location configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_ids: $json->device_ids ?? null,
                common_code_key: $json->common_code_key ?? null,
            );
        }

        public function __construct(
            /**
             * New device IDs where access codes should be created.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
            /**
             * Common code key to ensure PIN code reuse across devices.
             */
            public string|null $common_code_key = null,
        ) {}
    }
}

namespace Seam\Resources\AccessGrant\PendingMutations\UpdatingAccessTimes {
    /**
     * Previous access time configuration.
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
             * Previous end time for access.
             */
            public string|null $ends_at,
            /**
             * Previous start time for access.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * New access time configuration.
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

namespace Seam\Resources\AccessGrant\RequestedAccessMethods {
    enum Mode: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }
}

namespace Seam\Resources\AccessGrant\Warnings {
    /**
     * Indicates that the [access grant](https://docs.seam.co/use-cases/granting-access) is being deleted.
     */
    final class BeingDeleted extends \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
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
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
     * Indicates that the access grant should have access to more locations than it currently does. Access methods are being created for the missing locations.
     */
    final class UnderprovisionedAccess extends
        \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnderprovisionedAccess|null {
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
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
     * Indicates that the access grant has access to locations it should not have. Access methods are being removed from the extra locations.
     */
    final class OverprovisionedAccess extends
        \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(
            mixed $json,
        ): OverprovisionedAccess|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                failed_devices: array_map(
                    fn(
                        $f,
                    ) => \Seam\Resources\AccessGrant\Warnings\OverprovisionedAccess\FailedDevices::from_json(
                        $f,
                    ),
                    $json->failed_devices ?? [],
                ),
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
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * Devices whose access codes could not be revoked during reconciliation. Present when the provider does not support revoking an offline access code (e.g. Dormakaba oracode with exhausted override budget).
             *
             * @var list<\Seam\Resources\AccessGrant\Warnings\OverprovisionedAccess\FailedDevices>|null
             */
            public array|null $failed_devices = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the access times for this [access grant](https://docs.seam.co/use-cases/granting-access) are being updated.
     */
    final class UpdatingAccessTimes extends \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(mixed $json): UpdatingAccessTimes|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_ids: $json->access_method_ids ?? null,
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access methods being updated.
             *
             * @var list<string>|null
             */
            public array|null $access_method_ids,
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
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
     * Indicates that the requested PIN code was already in use on a device, so a different code was assigned.
     */
    final class RequestedCodeUnavailable extends
        \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(
            mixed $json,
        ): RequestedCodeUnavailable|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                message: $json->message ?? null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at,
            /**
             * ID of the device where the requested code was unavailable.
             */
            public string|null $device_id,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * The new PIN code that was assigned instead.
             */
            public string|null $new_code,
            /**
             * The originally requested PIN code that was unavailable.
             */
            public string|null $original_code,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
     * Indicates that a device in the access grant does not support access codes and was excluded from code materialization.
     */
    final class DeviceDoesNotSupportAccessCodes extends
        \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DeviceDoesNotSupportAccessCodes|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
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
             * ID of the device that does not support access codes.
             */
            public string|null $device_id,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
     * Indicates that a device in the access grant cannot program an access code for the grant's time range because of device-specific time constraints.
     */
    final class DeviceTimeConstraintsViolated extends
        \Seam\Resources\AccessGrant\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DeviceTimeConstraintsViolated|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                message: $json->message ?? null,
                reason: $json->reason ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at,
            /**
             * ID of the device whose time constraints the access grant violates.
             */
            public string|null $device_id,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Specific reason why the grant's times are not programmable on the device.
             *
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\DeviceTimeConstraintsViolated\Reason>|string|null
             */
            public string|null $reason,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AccessGrant\Warnings\WarningCode>|string|null
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
        case BEING_DELETED = "being_deleted";
        case UNDERPROVISIONED_ACCESS = "underprovisioned_access";
        case OVERPROVISIONED_ACCESS = "overprovisioned_access";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
        case REQUESTED_CODE_UNAVAILABLE = "requested_code_unavailable";
        case DEVICE_DOES_NOT_SUPPORT_ACCESS_CODES = "device_does_not_support_access_codes";
        case DEVICE_TIME_CONSTRAINTS_VIOLATED = "device_time_constraints_violated";
    }
}

namespace Seam\Resources\AccessGrant\Warnings\OverprovisionedAccess {
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

namespace Seam\Resources\AccessGrant\Warnings\DeviceTimeConstraintsViolated {
    enum Reason: string
    {
        case DURATION_EXCEEDS_MAX = "duration_exceeds_max";
        case TIMES_DO_NOT_MATCH_SLOTS = "times_do_not_match_slots";
        case ONGOING_NOT_SUPPORTED = "ongoing_not_supported";
    }
}
