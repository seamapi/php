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
            external_type: $json->external_type,
            external_type_display_name: $json->external_type_display_name,
            system_type: $json->system_type,
            system_type_display_name: $json->system_type_display_name,
            name: $json->name,
            created_at: $json->created_at,
            workspace_id: $json->workspace_id,
            connected_account_ids: $json->connected_account_ids,
        );
    }
  

    
    public function __construct(
        public string $acs_system_id,
        public string $external_type,
        public string $external_type_display_name,
        public string $system_type,
        public string $system_type_display_name,
        public string $name,
        public string $created_at,
        public string $workspace_id,
        public array $connected_account_ids,
    ) {
    }
  
}
