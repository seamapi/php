<?php

namespace Seam\Objects;

class UnmanagedAcsAccessGroup
{
    public static function from_json(mixed $json): UnmanagedAcsAccessGroup|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_group_type: $json->access_group_type ?? null,
            access_group_type_display_name: $json->access_group_type_display_name ??
                null,
            access_schedule: isset($json->access_schedule)
                ? UnmanagedAcsAccessGroupAccessSchedule::from_json(
                    $json->access_schedule,
                )
                : null,
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => UnmanagedAcsAccessGroupErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_managed: $json->is_managed ?? null,
            name: $json->name ?? null,
            pending_mutations: array_map(
                fn($p) => UnmanagedAcsAccessGroupPendingMutations::from_json(
                    $p,
                ),
                $json->pending_mutations ?? [],
            ),
            warnings: array_map(
                fn($w) => UnmanagedAcsAccessGroupWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_group_type,
        public string|null $access_group_type_display_name,
        public UnmanagedAcsAccessGroupAccessSchedule|null $access_schedule,
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
