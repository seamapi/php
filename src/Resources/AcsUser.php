<?php

namespace Seam\Resources;

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
        public AcsUserAccessSchedule|null $access_schedule,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $email,
        public string|null $email_address,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $full_name,
        public string|null $hid_acs_system_id,
        public bool|null $is_managed,
        public bool|null $is_suspended,
        public array $pending_mutations,
        public string|null $phone_number,
        public AcsUserSaltoKsMetadata|null $salto_ks_metadata,
        public AcsUserSaltoSpaceMetadata|null $salto_space_metadata,
        public string|null $user_identity_email_address,
        public string|null $user_identity_full_name,
        public string|null $user_identity_id,
        public string|null $user_identity_phone_number,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

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
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

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
        public string|null $acs_access_group_id,
        public string|null $acs_credential_id,
        public string|null $email_address,
        public string|null $ends_at,
        public string|null $full_name,
        public bool|null $is_suspended,
        public string|null $phone_number,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $acs_access_group_id,
        public string|null $created_at,
        public AcsUserFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public string|null $scheduled_at,
        public AcsUserTo|null $to,
        public string|null $variant,
    ) {}
}

class AcsUserSaltoKsMetadata
{
    public static function from_json(mixed $json): AcsUserSaltoKsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(is_subscribed: $json->is_subscribed ?? null);
    }

    public function __construct(public bool|null $is_subscribed) {}
}

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
        public bool|null $audit_openings,
        public string|null $user_id,
    ) {}
}

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
        public string|null $acs_access_group_id,
        public string|null $acs_credential_id,
        public string|null $email_address,
        public string|null $ends_at,
        public string|null $full_name,
        public bool|null $is_suspended,
        public string|null $phone_number,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
