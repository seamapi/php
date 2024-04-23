<?php

namespace Seam\Objects;

class Workspace
{
    
    public static function from_json(mixed $json): Workspace|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            connect_partner_name: $json->connect_partner_name ?? null,
            is_sandbox: $json->is_sandbox,
            name: $json->name,
            workspace_id: $json->workspace_id,
        );
    }
  

    
    public function __construct(
        public string | null $connect_partner_name,
        public bool $is_sandbox,
        public string $name,
        public string $workspace_id,
    ) {
    }
  
}
