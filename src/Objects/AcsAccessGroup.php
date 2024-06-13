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
            created_at: $json->created_at,
            display_name: $json->display_name,
            external_type: $json->external_type,
            external_type_display_name: $json->external_type_display_name,
            name: $json->name,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $access_group_type,
        public string $access_group_type_display_name,
        public string $acs_access_group_id,
        public string $acs_system_id,
        public string $created_at,
        public string $display_name,
        public string $external_type,
        public string $external_type_display_name,
        public string $name,
        public string $workspace_id
    ) {
    }
}
