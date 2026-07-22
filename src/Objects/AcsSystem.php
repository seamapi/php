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
            acs_access_group_count: $json->acs_access_group_count ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_count: $json->acs_user_count ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            connected_account_ids: $json->connected_account_ids ?? null,
            created_at: $json->created_at ?? null,
            default_credential_manager_acs_system_id: $json->default_credential_manager_acs_system_id ??
                null,
            errors: array_map(
                fn($e) => AcsSystemErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            image_alt_text: $json->image_alt_text ?? null,
            image_url: $json->image_url ?? null,
            is_credential_manager: $json->is_credential_manager ?? null,
            location: isset($json->location)
                ? AcsSystemLocation::from_json($json->location)
                : null,
            name: $json->name ?? null,
            system_type: $json->system_type ?? null,
            system_type_display_name: $json->system_type_display_name ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsSystemVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => AcsSystemWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public float|null $acs_access_group_count,
        public string|null $acs_system_id,
        public float|null $acs_user_count,
        public string|null $connected_account_id,
        public array|null $connected_account_ids,
        public string|null $created_at,
        public string|null $default_credential_manager_acs_system_id,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $image_alt_text,
        public string|null $image_url,
        public bool|null $is_credential_manager,
        public AcsSystemLocation|null $location,
        public string|null $name,
        public string|null $system_type,
        public string|null $system_type_display_name,
        public AcsSystemVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
