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
            workspace_id: $json->workspace_id,
            name: $json->name,
            is_sandbox: $json->is_sandbox,
            connect_partner_name: $json->connect_partner_name ?? null,
        );
    }
  

    
    public function __construct(
        public string $workspace_id,
        public string $name,
        public bool $is_sandbox,
        public string | null $connect_partner_name,
    ) {
    }
  
}
