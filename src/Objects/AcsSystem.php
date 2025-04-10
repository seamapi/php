<?php

namespace Seam\Objects;

class AcsSystem
{
    public static function from_json(mixed $json): AcsSystem|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_system_id: $json->acs_system_id,
            connected_account_id: $json->connected_account_id,
            connected_account_ids: $json->connected_account_ids,
            created_at: $json->created_at,
            errors: array_map(
                fn($e) => AcsSystemErrors::from_json($e),
                $json->errors ?? []
            ),
            image_alt_text: $json->image_alt_text,
            image_url: $json->image_url,
            is_credential_manager: $json->is_credential_manager,
            location: AcsSystemLocation::from_json($json->location),
            name: $json->name,
            warnings: array_map(
                fn($w) => AcsSystemWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id,
            acs_access_group_count: $json->acs_access_group_count ?? null,
            acs_user_count: $json->acs_user_count ?? null,
            can_add_acs_users_to_acs_access_groups: $json->can_add_acs_users_to_acs_access_groups ??
                null,
            can_automate_enrollment: $json->can_automate_enrollment ?? null,
            can_create_acs_access_groups: $json->can_create_acs_access_groups ??
                null,
            can_remove_acs_users_from_acs_access_groups: $json->can_remove_acs_users_from_acs_access_groups ??
                null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            system_type: $json->system_type ?? null,
            system_type_display_name: $json->system_type_display_name ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsSystemVisionlineMetadata::from_json(
                    $json->visionline_metadata
                )
                : null,
            default_credential_manager_acs_system_id: $json->default_credential_manager_acs_system_id ??
                null
        );
    }

    public function __construct(
        public string $acs_system_id,
        public string $connected_account_id,
        public array $connected_account_ids,
        public string $created_at,
        public array $errors,
        public string $image_alt_text,
        public string $image_url,
        public bool $is_credential_manager,
        public AcsSystemLocation $location,
        public string $name,
        public array $warnings,
        public string $workspace_id,
        public float|null $acs_access_group_count,
        public float|null $acs_user_count,
        public bool|null $can_add_acs_users_to_acs_access_groups,
        public bool|null $can_automate_enrollment,
        public bool|null $can_create_acs_access_groups,
        public bool|null $can_remove_acs_users_from_acs_access_groups,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $system_type,
        public string|null $system_type_display_name,
        public AcsSystemVisionlineMetadata|null $visionline_metadata,
        public string|null $default_credential_manager_acs_system_id
    ) {
    }
}
