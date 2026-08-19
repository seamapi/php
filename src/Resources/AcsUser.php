<?php

namespace Seam\Resources {
    /**
     * Represents a [user](https://docs.seam.co/low-level-apis/access-systems/user-management) in an [access system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * An access system user typically refers to an individual who requires access, like an employee or resident. Each user can possess multiple credentials that serve as their keys or identifiers for access. The type of credential can vary widely. For example, in the Salto system, a user can have a PIN code, a mobile app account, and a fob. In other platforms, it is not uncommon for a user to have more than one of the same credential type, such as multiple key cards. Additionally, these credentials can have a schedule or validity period.
     *
     * For details about how to configure users in your access system, see the corresponding [system integration guide](https://docs.seam.co/device-and-system-integration-guides#access-control-systems).
     */
    class AcsUser
    {
        public static function from_json(mixed $json): AcsUser|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AcsUser\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\AcsUser\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                access_schedule: isset($json->access_schedule)
                    ? \Seam\Resources\AcsUser\AccessSchedule::from_json(
                        $json->access_schedule,
                    )
                    : null,
                email: $json->email ?? null,
                email_address: $json->email_address ?? null,
                external_type: is_string($json->external_type ?? null)
                    ? \Seam\Resources\AcsUser\ExternalType::tryFrom(
                        $json->external_type,
                    )
                    : null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                full_name: $json->full_name ?? null,
                hid_acs_system_id: $json->hid_acs_system_id ?? null,
                is_suspended: $json->is_suspended ?? null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\AcsUser\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                phone_number: $json->phone_number ?? null,
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? \Seam\Resources\AcsUser\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
                salto_space_metadata: isset($json->salto_space_metadata)
                    ? \Seam\Resources\AcsUser\SaltoSpaceMetadata::from_json(
                        $json->salto_space_metadata,
                    )
                    : null,
                user_identity_email_address: $json->user_identity_email_address ??
                    null,
                user_identity_full_name: $json->user_identity_full_name ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                user_identity_phone_number: $json->user_identity_phone_number ??
                    null,
            );
        }

        public function __construct(
            /**
             * ID of the [access system](https://docs.seam.co/low-level-apis/access-systems) that contains the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $acs_user_id,
            /**
             * The ID of the connected account that is associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was created.
             */
            public string|null $created_at,
            /**
             * Display name for the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $display_name,
            /**
             * Errors associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             *
             * @var list<\Seam\Resources\AcsUser\Errors>
             */
            public array $errors,
            /**
             * Indicates whether Seam manages the access system user.
             */
            public true|null $is_managed,
            /**
             * Warnings associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             *
             * @var list<\Seam\Resources\AcsUser\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $workspace_id,
            /**
             * `starts_at` and `ends_at` timestamps for the [access system user's](https://docs.seam.co/low-level-apis/access-systems/user-management) access.
             */
            public \Seam\Resources\AcsUser\AccessSchedule|null $access_schedule = null,
            /**
             * @deprecated use email_address.
             */
            public string|null $email = null,
            /**
             * Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $email_address = null,
            /**
             * Brand-specific terminology for the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) type.
             */
            public \Seam\Resources\AcsUser\ExternalType|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * Full name of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $full_name = null,
            /**
             * ID of the HID access control system associated with the user.
             */
            public string|null $hid_acs_system_id = null,
            /**
             * Indicates whether the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) is currently [suspended](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users).
             */
            public bool|null $is_suspended = null,
            /**
             * Pending mutations associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Seam is in the process of pushing these mutations to the integrated access system.
             *
             * @var list<\Seam\Resources\AcsUser\PendingMutations>|null
             */
            public array|null $pending_mutations = null,
            /**
             * Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
             */
            public string|null $phone_number = null,
            /**
             * Salto KS-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public \Seam\Resources\AcsUser\SaltoKsMetadata|null $salto_ks_metadata = null,
            /**
             * Salto Space-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public \Seam\Resources\AcsUser\SaltoSpaceMetadata|null $salto_space_metadata = null,
            /**
             * Email address of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $user_identity_email_address = null,
            /**
             * Full name of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $user_identity_full_name = null,
            /**
             * ID of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
             */
            public string|null $user_identity_id = null,
            /**
             * Phone number of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
             */
            public string|null $user_identity_phone_number = null,
        ) {}
    }
}

namespace Seam\Resources\AcsUser {
    /**
     * `starts_at` and `ends_at` timestamps for the [access system user's](https://docs.seam.co/low-level-apis/access-systems/user-management) access.
     */
    class AccessSchedule
    {
        public static function from_json(mixed $json): AccessSchedule|null
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
             * Date and time at which the user's access ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $ends_at,
            /**
             * Date and time at which the user's access starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * Errors associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     */
    abstract class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsUser\Errors\ErrorCode::DELETED_EXTERNALLY
                    => \Seam\Resources\AcsUser\Errors\DeletedExternally::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\AcsUser\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Errors\ErrorCode::FAILED_TO_CREATE_ON_ACS_SYSTEM
                    => \Seam\Resources\AcsUser\Errors\FailedToCreateOnAcsSystem::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Errors\ErrorCode::FAILED_TO_UPDATE_ON_ACS_SYSTEM
                    => \Seam\Resources\AcsUser\Errors\FailedToUpdateOnAcsSystem::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Errors\ErrorCode::FAILED_TO_DELETE_ON_ACS_SYSTEM
                    => \Seam\Resources\AcsUser\Errors\FailedToDeleteOnAcsSystem::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Errors\ErrorCode::LATCH_CONFLICT_WITH_RESIDENT_USER
                    => \Seam\Resources\AcsUser\Errors\LatchConflictWithResidentUser::from_json(
                    $json,
                ),
                default => \Seam\Resources\AcsUser\Errors\Unknown::from_json(
                    $json,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            public \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Pending mutations associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Seam is in the process of pushing these mutations to the integrated access system.
     */
    abstract class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->mutation_code ?? null)
                ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                    $json->mutation_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::CREATING
                    => \Seam\Resources\AcsUser\PendingMutations\Creating::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::DELETING
                    => \Seam\Resources\AcsUser\PendingMutations\Deleting::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::DEFERRING_CREATION
                    => \Seam\Resources\AcsUser\PendingMutations\DeferringCreation::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::UPDATING_USER_INFORMATION
                    => \Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::UPDATING_ACCESS_SCHEDULE
                    => \Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::UPDATING_SUSPENSION_STATE
                    => \Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::UPDATING_GROUP_MEMBERSHIP
                    => \Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::DEFERRING_GROUP_MEMBERSHIP_UPDATE
                    => \Seam\Resources\AcsUser\PendingMutations\DeferringGroupMembershipUpdate::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\PendingMutations\MutationCode::UPDATING_CREDENTIAL_ASSIGNMENT
                    => \Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AcsUser\PendingMutations\Unknown::from_json(
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
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            public \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
        ) {}
    }

    /**
     * Salto KS-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     */
    class SaltoKsMetadata
    {
        public static function from_json(mixed $json): SaltoKsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(is_subscribed: $json->is_subscribed ?? null);
        }

        public function __construct(
            /**
             * Indicates whether the user holds an active subscription slot on the Salto KS site. Only subscribed users can unlock doors and count against the site's user-subscription limit. A user may not be subscribed because their access schedule has not started or has ended, the site has reached its subscription limit, or they were manually unsubscribed. This is distinct from `is_suspended`, which reflects whether the user has been explicitly blocked.
             */
            public bool|null $is_subscribed = null,
        ) {}
    }

    /**
     * Salto Space-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     */
    class SaltoSpaceMetadata
    {
        public static function from_json(mixed $json): SaltoSpaceMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                audit_openings: $json->audit_openings ?? null,
                user_id: $json->user_id ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether AuditOpenings is enabled for the user in the Salto Space access system.
             */
            public bool|null $audit_openings = null,
            /**
             * User ID in the Salto Space access system.
             */
            public string|null $user_id = null,
        ) {}
    }

    /**
     * Warnings associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     */
    abstract class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsUser\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\AcsUser\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Warnings\WarningCode::SALTO_KS_USER_NOT_SUBSCRIBED
                    => \Seam\Resources\AcsUser\Warnings\SaltoKsUserNotSubscribed::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Warnings\WarningCode::ACS_USER_INACTIVE
                    => \Seam\Resources\AcsUser\Warnings\AcsUserInactive::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_ACS_USER
                    => \Seam\Resources\AcsUser\Warnings\UnknownIssueWithAcsUser::from_json(
                    $json,
                ),
                \Seam\Resources\AcsUser\Warnings\WarningCode::LATCH_RESIDENT_USER
                    => \Seam\Resources\AcsUser\Warnings\LatchResidentUser::from_json(
                    $json,
                ),
                default => \Seam\Resources\AcsUser\Warnings\Unknown::from_json(
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
            public \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {}
    }

    enum ExternalType: string
    {
        case PTI_USER = "pti_user";
        case BRIVO_USER = "brivo_user";
        case HID_CREDENTIAL_MANAGER_USER = "hid_credential_manager_user";
        case SALTO_SITE_USER = "salto_site_user";
        case LATCH_USER = "latch_user";
        case DORMAKABA_COMMUNITY_USER = "dormakaba_community_user";
        case SALTO_SPACE_USER = "salto_space_user";
        case AVIGILON_ALTA_USER = "avigilon_alta_user";
        case KISI_USER = "kisi_user";
    }
}

namespace Seam\Resources\AcsUser\Errors {
    /**
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was deleted from the [access system](https://docs.seam.co/low-level-apis/access-systems) outside of Seam.
     */
    final class DeletedExternally extends \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(mixed $json): DeletedExternally|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) could not be subscribed on Salto KS because the subscription limit has been exceeded.
     */
    final class SaltoKsSubscriptionLimitExceeded extends
        \Seam\Resources\AcsUser\Errors
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
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was not created on the [access system](https://docs.seam.co/low-level-apis/access-systems). This is likely due to an internal unexpected error. Contact Seam [support](mailto:support@seam.co).
     */
    final class FailedToCreateOnAcsSystem extends \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(
            mixed $json,
        ): FailedToCreateOnAcsSystem|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was not updated on the [access system](https://docs.seam.co/low-level-apis/access-systems). This is likely due to an internal unexpected error. Contact Seam [support](mailto:support@seam.co).
     */
    final class FailedToUpdateOnAcsSystem extends \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(
            mixed $json,
        ): FailedToUpdateOnAcsSystem|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was not deleted on the [access system](https://docs.seam.co/low-level-apis/access-systems). This is likely due to an internal unexpected error. Contact Seam [support](mailto:support@seam.co).
     */
    final class FailedToDeleteOnAcsSystem extends \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(
            mixed $json,
        ): FailedToDeleteOnAcsSystem|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was created from the Seam API but also exists on Mission Control. This is unsupported. Contact Seam [support](mailto:support@seam.co).
     */
    final class LatchConflictWithResidentUser extends
        \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(
            mixed $json,
        ): LatchConflictWithResidentUser|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
     * Fallback for acs_user.errors values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AcsUser\Errors
    {
        public static function from_json(mixed $json): Unknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsUser\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsUser\Errors\ErrorCode|string|null $error_code,
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
        case DELETED_EXTERNALLY = "deleted_externally";
        case SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED = "salto_ks_subscription_limit_exceeded";
        case FAILED_TO_CREATE_ON_ACS_SYSTEM = "failed_to_create_on_acs_system";
        case FAILED_TO_UPDATE_ON_ACS_SYSTEM = "failed_to_update_on_acs_system";
        case FAILED_TO_DELETE_ON_ACS_SYSTEM = "failed_to_delete_on_acs_system";
        case LATCH_CONFLICT_WITH_RESIDENT_USER = "latch_conflict_with_resident_user";
    }
}

namespace Seam\Resources\AcsUser\PendingMutations {
    /**
     * Seam is in the process of pushing a user creation to the integrated access system.
     */
    final class Creating extends \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(mixed $json): Creating|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing a user deletion to the integrated access system.
     */
    final class Deleting extends \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(mixed $json): Deleting|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * User exists in Seam but has not been pushed to the provider yet. Will be created when a credential is issued.
     */
    final class DeferringCreation extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(mixed $json): DeferringCreation|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
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
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * Optional: When the user creation is scheduled to occur.
             */
            public string|null $scheduled_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    final class UpdatingUserInformation extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingUserInformation|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation\To::from_json(
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
             * Old access system user information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access system user information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an access schedule update to the integrated access system.
     */
    final class UpdatingAccessSchedule extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingAccessSchedule|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule\To::from_json(
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
             * Old access schedule information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access schedule information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing a suspension state update to the integrated access system.
     */
    final class UpdatingSuspensionState extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingSuspensionState|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState\To::from_json(
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
             * Old user suspension state information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New user suspension state information.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an access group membership update to the integrated access system.
     */
    final class UpdatingGroupMembership extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingGroupMembership|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership\To::from_json(
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
             * Old access group membership.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access group membership.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * A scheduled access group membership change is pending for this user.
     */
    final class DeferringGroupMembershipUpdate extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): DeferringGroupMembershipUpdate|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_access_group_id: $json->acs_access_group_id ?? null,
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                variant: is_string($json->variant ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\DeferringGroupMembershipUpdate\Variant::tryFrom(
                        $json->variant,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the access group involved in the scheduled change.
             */
            public string|null $acs_access_group_id,
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * Whether the user is scheduled to be added to or removed from the access group.
             */
            public \Seam\Resources\AcsUser\PendingMutations\DeferringGroupMembershipUpdate\Variant|null $variant,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of assigning or unassigning a credential to the user on the integrated access system.
     */
    final class UpdatingCredentialAssignment extends
        \Seam\Resources\AcsUser\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingCredentialAssignment|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment\To::from_json(
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
             * Previous credential assignment.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New credential assignment.
             */
            public \Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Fallback for acs_user.pending_mutations values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AcsUser\PendingMutations
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
                    ? \Seam\Resources\AcsUser\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
             */
            \Seam\Resources\AcsUser\PendingMutations\MutationCode|string|null $mutation_code,
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
        case DELETING = "deleting";
        case DEFERRING_CREATION = "deferring_creation";
        case UPDATING_USER_INFORMATION = "updating_user_information";
        case UPDATING_ACCESS_SCHEDULE = "updating_access_schedule";
        case UPDATING_SUSPENSION_STATE = "updating_suspension_state";
        case UPDATING_GROUP_MEMBERSHIP = "updating_group_membership";
        case DEFERRING_GROUP_MEMBERSHIP_UPDATE = "deferring_group_membership_update";
        case UPDATING_CREDENTIAL_ASSIGNMENT = "updating_credential_assignment";
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\UpdatingUserInformation {
    /**
     * Old access system user information.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                email_address: $json->email_address ?? null,
                full_name: $json->full_name ?? null,
                phone_number: $json->phone_number ?? null,
            );
        }

        public function __construct(
            /**
             * Email address of the access system user.
             */
            public string|null $email_address = null,
            /**
             * Full name of the access system user.
             */
            public string|null $full_name = null,
            /**
             * Phone number of the access system user.
             */
            public string|null $phone_number = null,
        ) {}
    }

    /**
     * New access system user information.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                email_address: $json->email_address ?? null,
                full_name: $json->full_name ?? null,
                phone_number: $json->phone_number ?? null,
            );
        }

        public function __construct(
            /**
             * Email address of the access system user.
             */
            public string|null $email_address = null,
            /**
             * Full name of the access system user.
             */
            public string|null $full_name = null,
            /**
             * Phone number of the access system user.
             */
            public string|null $phone_number = null,
        ) {}
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\UpdatingAccessSchedule {
    /**
     * Old access schedule information.
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
             * Starting time for the access schedule.
             */
            public string|null $ends_at,
            /**
             * Starting time for the access schedule.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * New access schedule information.
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
             * Starting time for the access schedule.
             */
            public string|null $ends_at,
            /**
             * Starting time for the access schedule.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\UpdatingSuspensionState {
    /**
     * Old user suspension state information.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(is_suspended: $json->is_suspended ?? null);
        }

        public function __construct(public bool|null $is_suspended) {}
    }

    /**
     * New user suspension state information.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(is_suspended: $json->is_suspended ?? null);
        }

        public function __construct(public bool|null $is_suspended) {}
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\UpdatingGroupMembership {
    /**
     * Old access group membership.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_access_group_id: $json->acs_access_group_id ?? null,
            );
        }

        public function __construct(
            /**
             * Old access group ID.
             */
            public string|null $acs_access_group_id,
        ) {}
    }

    /**
     * New access group membership.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_access_group_id: $json->acs_access_group_id ?? null,
            );
        }

        public function __construct(
            /**
             * New access group ID.
             */
            public string|null $acs_access_group_id,
        ) {}
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\DeferringGroupMembershipUpdate {
    enum Variant: string
    {
        case ADDING = "adding";
        case REMOVING = "removing";
    }
}

namespace Seam\Resources\AcsUser\PendingMutations\UpdatingCredentialAssignment {
    /**
     * Previous credential assignment.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
            );
        }

        public function __construct(
            /**
             * Previous credential ID.
             */
            public string|null $acs_credential_id,
        ) {}
    }

    /**
     * New credential assignment.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
            );
        }

        public function __construct(
            /**
             * New credential ID.
             */
            public string|null $acs_credential_id,
        ) {}
    }
}

namespace Seam\Resources\AcsUser\Warnings {
    /**
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) is being deleted from the [access system](https://docs.seam.co/low-level-apis/access-systems). This is a temporary state, and the access system user will be deleted shortly.
     */
    final class BeingDeleted extends \Seam\Resources\AcsUser\Warnings
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
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) is not subscribed on Salto KS, so they cannot unlock doors or perform any actions. This occurs when the their access schedule hasn’t started yet, if their access schedule has ended, if the site has reached its limit for active users (subscription slots), or if they have been manually unsubscribed.
     */
    final class SaltoKsUserNotSubscribed extends
        \Seam\Resources\AcsUser\Warnings
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsUserNotSubscribed|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) exists but is not currently able to gain access—for example, because their access schedule has not started yet or has ended, the access system has reached its limit for active users, or they have been unsubscribed or deactivated. Refer to the warning message for the provider-specific reason. This is distinct from `is_suspended`, which indicates the user has been explicitly blocked.
     */
    final class AcsUserInactive extends \Seam\Resources\AcsUser\Warnings
    {
        public static function from_json(mixed $json): AcsUserInactive|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * An unknown issue occurred while syncing the state of this [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) with the provider. This issue may affect the proper functioning of this user.
     */
    final class UnknownIssueWithAcsUser extends \Seam\Resources\AcsUser\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnknownIssueWithAcsUser|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was created on Latch Mission Control. Please use the Latch Mission Control to manage this user.
     */
    final class LatchResidentUser extends \Seam\Resources\AcsUser\Warnings
    {
        public static function from_json(mixed $json): LatchResidentUser|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Fallback for acs_user.warnings values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AcsUser\Warnings
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
                    ? \Seam\Resources\AcsUser\Warnings\WarningCode::tryFrom(
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
            \Seam\Resources\AcsUser\Warnings\WarningCode|string|null $warning_code,
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
        case SALTO_KS_USER_NOT_SUBSCRIBED = "salto_ks_user_not_subscribed";
        case ACS_USER_INACTIVE = "acs_user_inactive";
        case UNKNOWN_ISSUE_WITH_ACS_USER = "unknown_issue_with_acs_user";
        case LATCH_RESIDENT_USER = "latch_resident_user";
    }
}
