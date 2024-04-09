<?php

namespace Seam\Objects;

class Network
{
    
    public static function from_json(mixed $json): Network|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            network_id: $json->network_id,
            workspace_id: $json->workspace_id,
            display_name: $json->display_name,
            created_at: $json->created_at,
        );
    }
  

    
    public function __construct(
        public string $network_id,
        public string $workspace_id,
        public string $display_name,
        public string $created_at,
    ) {
    }
  
}
