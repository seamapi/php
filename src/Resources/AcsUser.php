<?php

namespace Seam\Resources;

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
            access_schedule: isset($json->access_schedule)
                ? AcsUserAccessSchedule::from_json($json->access_schedule)
                : null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            errors: array_map(
                fn($e) => AcsUserErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            full_name: $json->full_name ?? null,
            hid_acs_system_id: $json->hid_acs_system_id ?? null,
            is_managed: $json->is_managed ?? null,
            is_suspended: $json->is_suspended ?? null,
            pending_mutations: array_map(
                fn($p) => AcsUserPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            phone_number: $json->phone_number ?? null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? AcsUserSaltoKsMetadata::from_json($json->salto_ks_metadata)
                : null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? AcsUserSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata,
                )
                : null,
            user_identity_email_address: $json->user_identity_email_address ??
                null,
            user_identity_full_name: $json->user_identity_full_name ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            user_identity_phone_number: $json->user_identity_phone_number ??
                null,
            warnings: array_map(
                fn($w) => AcsUserWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        /**
         * `starts_at` and `ends_at` timestamps for the [access system user's](https://docs.seam.co/low-level-apis/access-systems/user-management) access.
         */
        public AcsUserAccessSchedule|null $access_schedule,
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
         * @deprecated use email_address.
         */
        public string|null $email,
        /**
         * Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $email_address,
        /**
         * Errors associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public array $errors,
        /**
         * Brand-specific terminology for the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) type.
         */
        public string|null $external_type,
        /**
         * Display name that corresponds to the brand-specific terminology for the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) type.
         */
        public string|null $external_type_display_name,
        /**
         * Full name of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $full_name,
        /**
         * ID of the HID access control system associated with the user.
         */
        public string|null $hid_acs_system_id,
        /**
         * Indicates whether Seam manages the access system user.
         */
        public bool|null $is_managed,
        /**
         * Indicates whether the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) is currently [suspended](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users).
         */
        public bool|null $is_suspended,
        /**
         * Pending mutations associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Seam is in the process of pushing these mutations to the integrated access system.
         */
        public array $pending_mutations,
        /**
         * Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
         */
        public string|null $phone_number,
        /**
         * Salto KS-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public AcsUserSaltoKsMetadata|null $salto_ks_metadata,
        /**
         * Salto Space-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public AcsUserSaltoSpaceMetadata|null $salto_space_metadata,
        /**
         * Email address of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $user_identity_email_address,
        /**
         * Full name of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $user_identity_full_name,
        /**
         * ID of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $user_identity_id,
        /**
         * Phone number of the user identity associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
         */
        public string|null $user_identity_phone_number,
        /**
         * Warnings associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public array $warnings,
        /**
         * ID of the workspace that contains the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * `starts_at` and `ends_at` timestamps for the [access system user's](https://docs.seam.co/low-level-apis/access-systems/user-management) access.
 */
class AcsUserAccessSchedule
{
    public static function from_json(mixed $json): AcsUserAccessSchedule|null
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
class AcsUserErrors
{
    public static function from_json(mixed $json): AcsUserErrors|null
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
        public string|null $error_code,
        /**
         * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
    ) {}
}

/**
 * Old access system user information.
 */
class AcsUserFrom
{
    public static function from_json(mixed $json): AcsUserFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            email_address: $json->email_address ?? null,
            ends_at: $json->ends_at ?? null,
            full_name: $json->full_name ?? null,
            is_suspended: $json->is_suspended ?? null,
            phone_number: $json->phone_number ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * Old access group ID.
         */
        public string|null $acs_access_group_id,
        /**
         * Previous credential ID.
         */
        public string|null $acs_credential_id,
        /**
         * Email address of the access system user.
         */
        public string|null $email_address,
        /**
         * Starting time for the access schedule.
         */
        public string|null $ends_at,
        /**
         * Full name of the access system user.
         */
        public string|null $full_name,
        public bool|null $is_suspended,
        /**
         * Phone number of the access system user.
         */
        public string|null $phone_number,
        /**
         * Starting time for the access schedule.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Pending mutations associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Seam is in the process of pushing these mutations to the integrated access system.
 */
class AcsUserPendingMutations
{
    public static function from_json(mixed $json): AcsUserPendingMutations|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AcsUserFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            scheduled_at: $json->scheduled_at ?? null,
            to: isset($json->to) ? AcsUserTo::from_json($json->to) : null,
            variant: $json->variant ?? null,
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
        public string|null $created_at,
        /**
         * Old access system user information.
         */
        public AcsUserFrom|null $from,
        /**
         * Detailed description of the mutation.
         */
        public string|null $message,
        /**
         * Mutation code to indicate that Seam is in the process of pushing a user creation to the integrated access system.
         */
        public string|null $mutation_code,
        /**
         * Optional: When the user creation is scheduled to occur.
         */
        public string|null $scheduled_at,
        /**
         * New access system user information.
         */
        public AcsUserTo|null $to,
        /**
         * Whether the user is scheduled to be added to or removed from the access group.
         */
        public string|null $variant,
    ) {}
}

/**
 * Salto KS-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
 */
class AcsUserSaltoKsMetadata
{
    public static function from_json(mixed $json): AcsUserSaltoKsMetadata|null
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
        public bool|null $is_subscribed,
    ) {}
}

/**
 * Salto Space-specific metadata associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
 */
class AcsUserSaltoSpaceMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsUserSaltoSpaceMetadata|null {
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
        public bool|null $audit_openings,
        /**
         * User ID in the Salto Space access system.
         */
        public string|null $user_id,
    ) {}
}

/**
 * New access system user information.
 */
class AcsUserTo
{
    public static function from_json(mixed $json): AcsUserTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            email_address: $json->email_address ?? null,
            ends_at: $json->ends_at ?? null,
            full_name: $json->full_name ?? null,
            is_suspended: $json->is_suspended ?? null,
            phone_number: $json->phone_number ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * New access group ID.
         */
        public string|null $acs_access_group_id,
        /**
         * New credential ID.
         */
        public string|null $acs_credential_id,
        /**
         * Email address of the access system user.
         */
        public string|null $email_address,
        /**
         * Starting time for the access schedule.
         */
        public string|null $ends_at,
        /**
         * Full name of the access system user.
         */
        public string|null $full_name,
        public bool|null $is_suspended,
        /**
         * Phone number of the access system user.
         */
        public string|null $phone_number,
        /**
         * Starting time for the access schedule.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Warnings associated with the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
 */
class AcsUserWarnings
{
    public static function from_json(mixed $json): AcsUserWarnings|null
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
        public string|null $warning_code,
    ) {}
}
