<?php

namespace Seam\Resources {
    /**
     * Represents an access method for an Access Grant. Access methods describe the modes of access, such as PIN codes, plastic cards, and mobile keys. For a mobile key, the access method also stores the URL for the associated Instant Key.
     */
    class AccessMethod
    {
        public static function from_json(mixed $json): AccessMethod|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AccessMethod\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_issued: $json->is_issued ?? null,
                issued_at: $json->issued_at ?? null,
                mode: is_string($json->mode ?? null)
                    ? \Seam\Resources\AccessMethod\Mode::tryFrom($json->mode)
                    : null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\AccessMethod\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                warnings: array_map(
                    fn($w) => \Seam\Resources\AccessMethod\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                client_session_token: $json->client_session_token ?? null,
                code: $json->code ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                instant_key_url: $json->instant_key_url ?? null,
                is_assignment_required: $json->is_assignment_required ?? null,
                is_encoding_required: $json->is_encoding_required ?? null,
                is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
                is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the access method was created.
             */
            public string|null $created_at,
            /**
             * Display name of the access method.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\AccessMethod\Errors>
             */
            public array $errors,
            /**
             * Indicates whether the access method has been issued.
             */
            public bool|null $is_issued,
            /**
             * Date and time at which the access method was issued.
             */
            public string|null $issued_at,
            /**
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public \Seam\Resources\AccessMethod\Mode|null $mode,
            /**
             * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
             *
             * @var list<\Seam\Resources\AccessMethod\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\AccessMethod\Warnings>
             */
            public array $warnings,
            /**
             * ID of the Seam workspace associated with the access method.
             */
            public string|null $workspace_id,
            /**
             * Token of the client session associated with the access method.
             */
            public string|null $client_session_token = null,
            /**
             * The actual PIN code for code access methods.
             */
            public string|null $code = null,
            /**
             * ID of the customization profile associated with the access method.
             */
            public string|null $customization_profile_id = null,
            /**
             * URL of the Instant Key for mobile key access methods.
             */
            public string|null $instant_key_url = null,
            /**
             * Indicates whether an existing card credential must be assigned to this access method before it can be issued. Only applies to card-mode access methods on systems that support credential assignment.
             */
            public bool|null $is_assignment_required = null,
            /**
             * Indicates whether encoding with an card encoder is required to issue or reissue the plastic card associated with the access method.
             */
            public bool|null $is_encoding_required = null,
            /**
             * Indicates whether the access method is ready for card assignment. This is true when the access method is in card mode, has not yet been issued, and the system supports credential assignment.
             */
            public bool|null $is_ready_for_assignment = null,
            /**
             * Indicates whether the access method is ready to be encoded. This is true when the credential has been created and the card has not yet been issued.
             */
            public bool|null $is_ready_for_encoding = null,
        ) {}
    }
}

namespace Seam\Resources\AccessMethod {
    /**
     * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
     */
    abstract class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\AccessMethod\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessMethod\Errors\ErrorCode::FAILED_TO_ISSUE
                    => \Seam\Resources\AccessMethod\Errors\FailedToIssue::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AccessMethod\Errors\Unknown::from_json(
                    $json,
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
            public \Seam\Resources\AccessMethod\Errors\ErrorCode|string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
     */
    abstract class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->mutation_code ?? null)
                ? \Seam\Resources\AccessMethod\PendingMutations\MutationCode::tryFrom(
                    $json->mutation_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessMethod\PendingMutations\MutationCode::PROVISIONING_ACCESS
                    => \Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess::from_json(
                    $json,
                ),
                \Seam\Resources\AccessMethod\PendingMutations\MutationCode::REVOKING_ACCESS
                    => \Seam\Resources\AccessMethod\PendingMutations\RevokingAccess::from_json(
                    $json,
                ),
                \Seam\Resources\AccessMethod\PendingMutations\MutationCode::UPDATING_ACCESS_TIMES
                    => \Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AccessMethod\PendingMutations\Unknown::from_json(
                    $json,
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
             * Mutation code to indicate that Seam is in the process of provisioning access for this access method on new devices.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\MutationCode|string|null $mutation_code,
        ) {}
    }

    /**
     * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
     */
    abstract class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AccessMethod\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\AccessMethod\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\AccessMethod\Warnings\WarningCode::UPDATING_ACCESS_TIMES
                    => \Seam\Resources\AccessMethod\Warnings\UpdatingAccessTimes::from_json(
                    $json,
                ),
                \Seam\Resources\AccessMethod\Warnings\WarningCode::PULLED_BACKUP_ACCESS_CODE
                    => \Seam\Resources\AccessMethod\Warnings\PulledBackupAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\AccessMethod\Warnings\WarningCode::DELAY_IN_ISSUING
                    => \Seam\Resources\AccessMethod\Warnings\DelayInIssuing::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AccessMethod\Warnings\Unknown::from_json(
                    $json,
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
            public \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
        ) {}
    }

    enum Mode: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }
}

namespace Seam\Resources\AccessMethod\Errors {
    /**
     * Indicates that Seam was unable to issue this [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant) before its access grant started, so the recipient may be unable to access the space. This usually points to a problem that needs attention, such as an offline or disconnected device. Seam keeps retrying, and this error clears automatically if the access method is eventually issued.
     */
    final class FailedToIssue extends \Seam\Resources\AccessMethod\Errors
    {
        public static function from_json(mixed $json): FailedToIssue|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AccessMethod\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            \Seam\Resources\AccessMethod\Errors\ErrorCode|string|null $error_code,
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
     * Fallback for access_method.errors values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AccessMethod\Errors
    {
        public static function from_json(mixed $json): Unknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AccessMethod\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            \Seam\Resources\AccessMethod\Errors\ErrorCode|string|null $error_code,
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

    enum ErrorCode: string
    {
        case FAILED_TO_ISSUE = "failed_to_issue";
    }
}

namespace Seam\Resources\AccessMethod\PendingMutations {
    /**
     * Seam is in the process of provisioning access for this access method on new devices.
     */
    final class ProvisioningAccess extends
        \Seam\Resources\AccessMethod\PendingMutations
    {
        public static function from_json(mixed $json): ProvisioningAccess|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AccessMethod\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess\To::from_json(
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
             * Previous device configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of provisioning access for this access method on new devices.
             */
            \Seam\Resources\AccessMethod\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New device configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of revoking access for this access method from devices.
     */
    final class RevokingAccess extends
        \Seam\Resources\AccessMethod\PendingMutations
    {
        public static function from_json(mixed $json): RevokingAccess|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessMethod\PendingMutations\RevokingAccess\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AccessMethod\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessMethod\PendingMutations\RevokingAccess\To::from_json(
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
             * Previous device configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\RevokingAccess\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of provisioning access for this access method on new devices.
             */
            \Seam\Resources\AccessMethod\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New device configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\RevokingAccess\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of updating the access times for this access method.
     */
    final class UpdatingAccessTimes extends
        \Seam\Resources\AccessMethod\PendingMutations
    {
        public static function from_json(mixed $json): UpdatingAccessTimes|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AccessMethod\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes\To::from_json(
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
             * Previous access time configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of provisioning access for this access method on new devices.
             */
            \Seam\Resources\AccessMethod\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access time configuration.
             */
            public \Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Fallback for access_method.pending_mutations values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AccessMethod\PendingMutations
    {
        public static function from_json(mixed $json): Unknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AccessMethod\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
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
             * Mutation code to indicate that Seam is in the process of provisioning access for this access method on new devices.
             */
            \Seam\Resources\AccessMethod\PendingMutations\MutationCode|string|null $mutation_code,
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
        case PROVISIONING_ACCESS = "provisioning_access";
        case REVOKING_ACCESS = "revoking_access";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
    }
}

namespace Seam\Resources\AccessMethod\PendingMutations\ProvisioningAccess {
    /**
     * Previous device configuration.
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
             * Previous device IDs where access was provisioned.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
        ) {}
    }

    /**
     * New device configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(device_ids: $json->device_ids ?? null);
        }

        public function __construct(
            /**
             * New device IDs where access is being provisioned.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
        ) {}
    }
}

namespace Seam\Resources\AccessMethod\PendingMutations\RevokingAccess {
    /**
     * Previous device configuration.
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
             * Previous device IDs where access existed.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
        ) {}
    }

    /**
     * New device configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(device_ids: $json->device_ids ?? null);
        }

        public function __construct(
            /**
             * New device IDs where access should remain.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
        ) {}
    }
}

namespace Seam\Resources\AccessMethod\PendingMutations\UpdatingAccessTimes {
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

namespace Seam\Resources\AccessMethod\Warnings {
    /**
     * Indicates that the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant) is being deleted.
     */
    final class BeingDeleted extends \Seam\Resources\AccessMethod\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the access times for this [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant) are being updated.
     */
    final class UpdatingAccessTimes extends
        \Seam\Resources\AccessMethod\Warnings
    {
        public static function from_json(mixed $json): UpdatingAccessTimes|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that all attempts to create an access code on this device before the start time failed and a backup access code was used to ensure access was provided in time.
     */
    final class PulledBackupAccessCode extends
        \Seam\Resources\AccessMethod\Warnings
    {
        public static function from_json(
            mixed $json,
        ): PulledBackupAccessCode|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
                            $json->warning_code,
                        ) ?? $json->warning_code
                    : null,
                original_access_method_id: $json->original_access_method_id ??
                    null,
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
            \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
            /**
             * ID of the original access method from which this backup access method was split, if applicable.
             */
            public string|null $original_access_method_id = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that Seam has not yet issued this [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant), even though its access grant is about to begin, so access may not be ready when the recipient arrives. Seam is still attempting to issue it, and this warning clears automatically once issuance succeeds.
     */
    final class DelayInIssuing extends \Seam\Resources\AccessMethod\Warnings
    {
        public static function from_json(mixed $json): DelayInIssuing|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Fallback for access_method.warnings values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AccessMethod\Warnings
    {
        public static function from_json(mixed $json): Unknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AccessMethod\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AccessMethod\Warnings\WarningCode|string|null $warning_code,
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
        case UPDATING_ACCESS_TIMES = "updating_access_times";
        case PULLED_BACKUP_ACCESS_CODE = "pulled_backup_access_code";
        case DELAY_IN_ISSUING = "delay_in_issuing";
    }
}
