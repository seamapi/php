<?php

namespace Seam\Objects;

class AcsAccessGroup
{
    public static function from_json(mixed $json): AcsAccessGroup|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_group_type: $json->access_group_type,
            access_group_type_display_name: $json->access_group_type_display_name,
            acs_access_group_id: $json->acs_access_group_id,
            acs_system_id: $json->acs_system_id,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsAccessGroupErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type,
            external_type_display_name: $json->external_type_display_name,
            is_managed: $json->is_managed,
            name: $json->name,
            pending_mutations: array_map(
                fn($p) => AcsAccessGroupPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            warnings: array_map(
                fn($w) => AcsAccessGroupWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id,
            access_schedule: isset($json->access_schedule)
                ? AcsAccessGroupAccessSchedule::from_json(
                    $json->access_schedule,
                )
                : null,
        );
    }

    public function __construct(
        public string $access_group_type,
        public string $access_group_type_display_name,
        public string $acs_access_group_id,
        public string $acs_system_id,
        public string $connected_account_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public string $external_type,
        public string $external_type_display_name,
        public bool $is_managed,
        public string $name,
        public array $pending_mutations,
        public array $warnings,
        public string $workspace_id,
        public AcsAccessGroupAccessSchedule|null $access_schedule,
    ) {}
}
