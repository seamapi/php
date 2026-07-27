<?php

namespace Seam\Resources;

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
        public string|null $access_group_type,
        public string|null $access_group_type_display_name,
        public AcsAccessGroupAccessSchedule|null $access_schedule,
        public string|null $acs_access_group_id,
        public string|null $acs_system_id,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public bool|null $is_managed,
        public string|null $name,
        public array $pending_mutations,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

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
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

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
        public string|null $acs_entrance_id,
        public string|null $acs_user_id,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $acs_user_id,
        public string|null $created_at,
        public AcsAccessGroupFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public AcsAccessGroupTo|null $to,
        public string|null $variant,
    ) {}
}

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
        public string|null $acs_entrance_id,
        public string|null $acs_user_id,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}

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
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
