<?php

namespace Seam\Resources;

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
            access_group_type: $json->access_group_type ?? null,
            access_group_type_display_name: $json->access_group_type_display_name ??
                null,
            access_schedule: isset($json->access_schedule)
                ? AcsAccessGroupAccessSchedule::from_json(
                    $json->access_schedule,
                )
                : null,
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => AcsAccessGroupErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_managed: $json->is_managed ?? null,
            name: $json->name ?? null,
            pending_mutations: array_map(
                fn($p) => AcsAccessGroupPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            warnings: array_map(
                fn($w) => AcsAccessGroupWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        /**
         * @deprecated Use `external_type`.
         */
        public string|null $access_group_type,
        /**
         * @deprecated Use `external_type_display_name`.
         */
        public string|null $access_group_type_display_name,
        /**
         * `starts_at` and `ends_at` timestamps for the access group's access.
         */
        public AcsAccessGroupAccessSchedule|null $access_schedule,
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
         */
        public array $errors,
        /**
         * Brand-specific terminology for the access group type.
         */
        public string|null $external_type,
        /**
         * Display name that corresponds to the brand-specific terminology for the access group type.
         */
        public string|null $external_type_display_name,
        /**
         * Indicates whether Seam manages the access group.
         */
        public bool|null $is_managed,
        /**
         * Name of the access group.
         */
        public string|null $name,
        /**
         * Collection of pending mutations for the access group. Represents operations that have been requested but not yet completed on the integrated access system.
         */
        public array $pending_mutations,
        /**
         * Warnings associated with the `acs_access_group`.
         */
        public array $warnings,
        /**
         * ID of the workspace that contains the access group.
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * `starts_at` and `ends_at` timestamps for the access group's access.
 */
class AcsAccessGroupAccessSchedule
{
    public static function from_json(
        mixed $json,
    ): AcsAccessGroupAccessSchedule|null {
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
class AcsAccessGroupErrors
{
    public static function from_json(mixed $json): AcsAccessGroupErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
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
         */
        public string|null $error_code,
        /**
         * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
    ) {}
}

/**
 * Old access group information.
 */
class AcsAccessGroupFrom
{
    public static function from_json(mixed $json): AcsAccessGroupFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_id: $json->acs_entrance_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * Old entrance ID.
         */
        public string|null $acs_entrance_id,
        /**
         * Old user ID.
         */
        public string|null $acs_user_id,
        /**
         * Ending time for the access schedule.
         */
        public string|null $ends_at,
        /**
         * Name of the access group.
         */
        public string|null $name,
        /**
         * Starting time for the access schedule.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Collection of pending mutations for the access group. Represents operations that have been requested but not yet completed on the integrated access system.
 */
class AcsAccessGroupPendingMutations
{
    public static function from_json(
        mixed $json,
    ): AcsAccessGroupPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_user_id: $json->acs_user_id ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AcsAccessGroupFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to)
                ? AcsAccessGroupTo::from_json($json->to)
                : null,
            variant: $json->variant ?? null,
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
        public string|null $created_at,
        /**
         * Old access group information.
         */
        public AcsAccessGroupFrom|null $from,
        /**
         * Detailed description of the mutation.
         */
        public string|null $message,
        /**
         * Mutation code to indicate that Seam is in the process of pushing an access group creation to the integrated access system.
         */
        public string|null $mutation_code,
        /**
         * New access group information.
         */
        public AcsAccessGroupTo|null $to,
        /**
         * Whether the user is scheduled to be added to or removed from this access group.
         */
        public string|null $variant,
    ) {}
}

/**
 * New access group information.
 */
class AcsAccessGroupTo
{
    public static function from_json(mixed $json): AcsAccessGroupTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_id: $json->acs_entrance_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * New entrance ID.
         */
        public string|null $acs_entrance_id,
        /**
         * New user ID.
         */
        public string|null $acs_user_id,
        /**
         * Ending time for the access schedule.
         */
        public string|null $ends_at,
        /**
         * Name of the access group.
         */
        public string|null $name,
        /**
         * Starting time for the access schedule.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Warnings associated with the `acs_access_group`.
 */
class AcsAccessGroupWarnings
{
    public static function from_json(mixed $json): AcsAccessGroupWarnings|null
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
        public string|null $created_at,
        /**
         * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
        /**
         * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
         */
        public string|null $warning_code,
    ) {}
}
