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
            can_add_acs_users_to_acs_access_groups: $json->can_add_acs_users_to_acs_access_groups ??
                null,
            can_automate_enrollment: $json->can_automate_enrollment ?? null,
            can_create_acs_access_groups: $json->can_create_acs_access_groups ??
                null,
            can_remove_acs_users_from_acs_access_groups: $json->can_remove_acs_users_from_acs_access_groups ??
                null,
            connected_account_ids: $json->connected_account_ids,
            created_at: $json->created_at,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            image_alt_text: $json->image_alt_text,
            image_url: $json->image_url,
            name: $json->name,
            system_type: $json->system_type ?? null,
            system_type_display_name: $json->system_type_display_name ?? null,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $acs_system_id,
        public bool|null $can_add_acs_users_to_acs_access_groups,
        public bool|null $can_automate_enrollment,
        public bool|null $can_create_acs_access_groups,
        public bool|null $can_remove_acs_users_from_acs_access_groups,
        public array $connected_account_ids,
        public string $created_at,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string $image_alt_text,
        public string $image_url,
        public string $name,
        public string|null $system_type,
        public string|null $system_type_display_name,
        public string $workspace_id
    ) {
    }
}
