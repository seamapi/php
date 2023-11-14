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
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            workspace_id: $json->workspace_id ?? null,
            name: $json->name ?? null,
            access_group_type: $json->access_group_type ?? null,
            access_group_type_display_name: $json->access_group_type_display_name ??
                null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            created_at: $json->created_at ?? null
        );
    }

    public function __construct(
        public string|null $acs_access_group_id,
        public string|null $acs_system_id,
        public string|null $workspace_id,
        public string|null $name,
        public string|null $access_group_type,
        public string|null $access_group_type_display_name,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $created_at
    ) {
    }
}
