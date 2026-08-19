<?php

namespace Seam\Resources {
    /**
     * Group that defines the entrances to which a set of users has access and, in some cases, the access schedule for these entrances and users.
     *
     * Some access control systems use [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups), which are sets of users, combined with sets of permissions. These permissions include both the set of areas or assets that the users can access and the schedule during which the users can access these areas or assets. Instead of assigning access rights individually to each access control system user, which can be time-consuming and error-prone, administrators can assign users to an access group, thereby ensuring that the users inherit all the permissions associated with the access group. Using access groups streamlines the process of managing large numbers of access control system users, especially in bigger organizations or complexes.
     *
     * To learn whether your access control system supports access groups, see the corresponding [system integration guide](https://docs.seam.co/device-and-system-integration-guides#access-control-systems).
     */
    class AcsAccessGroup
    {
        public static function from_json(mixed $json): AcsAccessGroup|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_group_type: is_string($json->access_group_type ?? null)
                    ? \Seam\Resources\AcsAccessGroup\AccessGroupType::tryFrom(
                        $json->access_group_type,
                    )
                    : null,
                access_group_type_display_name: $json->access_group_type_display_name ??
                    null,
                acs_access_group_id: $json->acs_access_group_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AcsAccessGroup\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                external_type: is_string($json->external_type ?? null)
                    ? \Seam\Resources\AcsAccessGroup\ExternalType::tryFrom(
                        $json->external_type,
                    )
                    : null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                is_managed: $json->is_managed ?? null,
                name: $json->name ?? null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\AcsAccessGroup\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\AcsAccessGroup\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                access_schedule: isset($json->access_schedule)
                    ? \Seam\Resources\AcsAccessGroup\AccessSchedule::from_json(
                        $json->access_schedule,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * @deprecated Use `external_type`.
             */
            public \Seam\Resources\AcsAccessGroup\AccessGroupType|null $access_group_type,
            /**
             * @deprecated Use `external_type_display_name`.
             */
            public string|null $access_group_type_display_name,
            /**
             * ID of the access group.
             */
            public string|null $acs_access_group_id,
            /**
             * ID of the access control system that contains the access group.
             */
            public string|null $acs_system_id,
            /**
             * ID of the connected account that contains the access group.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the access group was created.
             */
            public string|null $created_at,
            /**
             * Display name for the access group.
             */
            public string|null $display_name,
            /**
             * Errors associated with the `acs_access_group`.
             *
             * @var list<\Seam\Resources\AcsAccessGroup\Errors>
             */
            public array $errors,
            /**
             * Brand-specific terminology for the access group type.
             */
            public \Seam\Resources\AcsAccessGroup\ExternalType|null $external_type,
            /**
             * Display name that corresponds to the brand-specific terminology for the access group type.
             */
            public string|null $external_type_display_name,
            /**
             * Indicates whether Seam manages the access group.
             */
            public true|null $is_managed,
            /**
             * Name of the access group.
             */
            public string|null $name,
            /**
             * Collection of pending mutations for the access group. Represents operations that have been requested but not yet completed on the integrated access system.
             *
             * @var list<\Seam\Resources\AcsAccessGroup\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Warnings associated with the `acs_access_group`.
             *
             * @var list<\Seam\Resources\AcsAccessGroup\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the access group.
             */
            public string|null $workspace_id,
            /**
             * `starts_at` and `ends_at` timestamps for the access group's access.
             */
            public \Seam\Resources\AcsAccessGroup\AccessSchedule|null $access_schedule = null,
        ) {}
    }
}

namespace Seam\Resources\AcsAccessGroup {
    /**
     * `starts_at` and `ends_at` timestamps for the access group's access.
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
     * Errors associated with the `acs_access_group`.
     */
    abstract class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\AcsAccessGroup\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsAccessGroup\Errors\ErrorCode::FAILED_TO_CREATE_ON_ACS_SYSTEM
                    => \Seam\Resources\AcsAccessGroup\Errors\FailedToCreateOnAcsSystem::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AcsAccessGroup\Errors\Unknown::from_json(
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
            public \Seam\Resources\AcsAccessGroup\Errors\ErrorCode|string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Collection of pending mutations for the access group. Represents operations that have been requested but not yet completed on the integrated access system.
     */
    abstract class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->mutation_code ?? null)
                ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                    $json->mutation_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::CREATING
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\Creating::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::DELETING
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\Deleting::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::DEFERRING_DELETION
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\DeferringDeletion::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::UPDATING_GROUP_INFORMATION
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::UPDATING_ACCESS_SCHEDULE
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::UPDATING_USER_MEMBERSHIP
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::UPDATING_ENTRANCE_MEMBERSHIP
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership::from_json(
                    $json,
                ),
                \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::DEFERRING_USER_MEMBERSHIP_UPDATE
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\DeferringUserMembershipUpdate::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\AcsAccessGroup\PendingMutations\Unknown::from_json(
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
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
        ) {}
    }

    /**
     * Warnings associated with the `acs_access_group`.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
            );
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
            public \Seam\Resources\AcsAccessGroup\Warnings\WarningCode|null $warning_code,
        ) {}
    }

    enum AccessGroupType: string
    {
        case PTI_UNIT = "pti_unit";
        case PTI_ACCESS_LEVEL = "pti_access_level";
        case SALTO_KS_ACCESS_GROUP = "salto_ks_access_group";
        case BRIVO_GROUP = "brivo_group";
        case SALTO_SPACE_GROUP = "salto_space_group";
        case DORMAKABA_COMMUNITY_ACCESS_GROUP = "dormakaba_community_access_group";
        case DORMAKABA_AMBIANCE_ACCESS_GROUP = "dormakaba_ambiance_access_group";
        case AVIGILON_ALTA_GROUP = "avigilon_alta_group";
        case KISI_ACCESS_GROUP = "kisi_access_group";
        case AKILES_MEMBER_GROUP = "akiles_member_group";
    }

    enum ExternalType: string
    {
        case PTI_UNIT = "pti_unit";
        case PTI_ACCESS_LEVEL = "pti_access_level";
        case SALTO_KS_ACCESS_GROUP = "salto_ks_access_group";
        case BRIVO_GROUP = "brivo_group";
        case SALTO_SPACE_GROUP = "salto_space_group";
        case DORMAKABA_COMMUNITY_ACCESS_GROUP = "dormakaba_community_access_group";
        case DORMAKABA_AMBIANCE_ACCESS_GROUP = "dormakaba_ambiance_access_group";
        case AVIGILON_ALTA_GROUP = "avigilon_alta_group";
        case KISI_ACCESS_GROUP = "kisi_access_group";
        case AKILES_MEMBER_GROUP = "akiles_member_group";
    }
}

namespace Seam\Resources\AcsAccessGroup\Errors {
    /**
     * Indicates that the [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups) was not created on the [access system](https://docs.seam.co/low-level-apis/access-systems). This is likely due to an internal unexpected error. Contact Seam [support](mailto:support@seam.co).
     */
    final class FailedToCreateOnAcsSystem extends
        \Seam\Resources\AcsAccessGroup\Errors
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
                    ? \Seam\Resources\AcsAccessGroup\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsAccessGroup\Errors\ErrorCode|string|null $error_code,
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
     * Fallback for acs_access_group.errors values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AcsAccessGroup\Errors
    {
        public static function from_json(mixed $json): Unknown|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\Errors\ErrorCode::tryFrom(
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
            \Seam\Resources\AcsAccessGroup\Errors\ErrorCode|string|null $error_code,
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
        case FAILED_TO_CREATE_ON_ACS_SYSTEM = "failed_to_create_on_acs_system";
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations {
    /**
     * Seam is in the process of pushing an access group creation to the integrated access system.
     */
    final class Creating extends \Seam\Resources\AcsAccessGroup\PendingMutations
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
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an access group deletion to the integrated access system.
     */
    final class Deleting extends \Seam\Resources\AcsAccessGroup\PendingMutations
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
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * This access group is scheduled for automatic deletion when its access window expires.
     */
    final class DeferringDeletion extends
        \Seam\Resources\AcsAccessGroup\PendingMutations
    {
        public static function from_json(mixed $json): DeferringDeletion|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an access group information update to the integrated access system.
     */
    final class UpdatingGroupInformation extends
        \Seam\Resources\AcsAccessGroup\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingGroupInformation|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation\To::from_json(
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
             * Old access group information.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access group information.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation\To|null $to,
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
        \Seam\Resources\AcsAccessGroup\PendingMutations
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
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule\To::from_json(
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
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New access schedule information.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing a user membership update to the integrated access system.
     */
    final class UpdatingUserMembership extends
        \Seam\Resources\AcsAccessGroup\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingUserMembership|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership\To::from_json(
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
             * Old user membership.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New user membership.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Seam is in the process of pushing an entrance membership update to the integrated access system.
     */
    final class UpdatingEntranceMembership extends
        \Seam\Resources\AcsAccessGroup\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): UpdatingEntranceMembership|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership\To::from_json(
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
             * Old entrance membership.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * New entrance membership.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership\To|null $to,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * A scheduled user membership change is pending for this access group.
     */
    final class DeferringUserMembershipUpdate extends
        \Seam\Resources\AcsAccessGroup\PendingMutations
    {
        public static function from_json(
            mixed $json,
        ): DeferringUserMembershipUpdate|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_user_id: $json->acs_user_id ?? null,
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
                            $json->mutation_code,
                        ) ?? $json->mutation_code
                    : null,
                variant: is_string($json->variant ?? null)
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\DeferringUserMembershipUpdate\Variant::tryFrom(
                        $json->variant,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the user involved in the scheduled change.
             */
            public string|null $acs_user_id,
            /**
             * Date and time at which the mutation was created.
             */
            string|null $created_at,
            /**
             * Detailed description of the mutation.
             */
            string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
            /**
             * Whether the user is scheduled to be added to or removed from this access group.
             */
            public \Seam\Resources\AcsAccessGroup\PendingMutations\DeferringUserMembershipUpdate\Variant|null $variant,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                mutation_code: $mutation_code,
            );
        }
    }

    /**
     * Fallback for acs_access_group.pending_mutations values introduced after this SDK version.
     */
    final class Unknown extends \Seam\Resources\AcsAccessGroup\PendingMutations
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
                    ? \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode::tryFrom(
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
             * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
             */
            \Seam\Resources\AcsAccessGroup\PendingMutations\MutationCode|string|null $mutation_code,
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
        case DEFERRING_DELETION = "deferring_deletion";
        case UPDATING_GROUP_INFORMATION = "updating_group_information";
        case UPDATING_ACCESS_SCHEDULE = "updating_access_schedule";
        case UPDATING_USER_MEMBERSHIP = "updating_user_membership";
        case UPDATING_ENTRANCE_MEMBERSHIP = "updating_entrance_membership";
        case DEFERRING_USER_MEMBERSHIP_UPDATE = "deferring_user_membership_update";
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingGroupInformation {
    /**
     * Old access group information.
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
             * Name of the access group.
             */
            public string|null $name = null,
        ) {}
    }

    /**
     * New access group information.
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
             * Name of the access group.
             */
            public string|null $name = null,
        ) {}
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingAccessSchedule {
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
             * Ending time for the access schedule.
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
             * Ending time for the access schedule.
             */
            public string|null $ends_at,
            /**
             * Starting time for the access schedule.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingUserMembership {
    /**
     * Old user membership.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(acs_user_id: $json->acs_user_id ?? null);
        }

        public function __construct(
            /**
             * Old user ID.
             */
            public string|null $acs_user_id,
        ) {}
    }

    /**
     * New user membership.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(acs_user_id: $json->acs_user_id ?? null);
        }

        public function __construct(
            /**
             * New user ID.
             */
            public string|null $acs_user_id,
        ) {}
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations\UpdatingEntranceMembership {
    /**
     * Old entrance membership.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(acs_entrance_id: $json->acs_entrance_id ?? null);
        }

        public function __construct(
            /**
             * Old entrance ID.
             */
            public string|null $acs_entrance_id,
        ) {}
    }

    /**
     * New entrance membership.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(acs_entrance_id: $json->acs_entrance_id ?? null);
        }

        public function __construct(
            /**
             * New entrance ID.
             */
            public string|null $acs_entrance_id,
        ) {}
    }
}

namespace Seam\Resources\AcsAccessGroup\PendingMutations\DeferringUserMembershipUpdate {
    enum Variant: string
    {
        case ADDING = "adding";
        case REMOVING = "removing";
    }
}

namespace Seam\Resources\AcsAccessGroup\Warnings {
    enum WarningCode: string
    {
        case UNKNOWN_ISSUE_WITH_ACS_ACCESS_GROUP = "unknown_issue_with_acs_access_group";
        case BEING_DELETED = "being_deleted";
    }
}
