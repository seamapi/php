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
            acs_access_group_id: $json->acs_access_group_id,
            acs_system_id: $json->acs_system_id,
            workspace_id: $json->workspace_id,
            name: $json->name,
            access_group_type: $json->access_group_type,
            access_group_type_display_name: $json->access_group_type_display_name,
            external_type: $json->external_type,
            external_type_display_name: $json->external_type_display_name,
            created_at: $json->created_at,
        );
    }
  

    
    public function __construct(
        public string $acs_access_group_id,
        public string $acs_system_id,
        public string $workspace_id,
        public string $name,
        public string $access_group_type,
        public string $access_group_type_display_name,
        public string $external_type,
        public string $external_type_display_name,
        public string $created_at,
    ) {
    }
  
}
