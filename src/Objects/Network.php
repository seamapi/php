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
            created_at: $json->created_at,
            display_name: $json->display_name,
            network_id: $json->network_id,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $created_at,
        public string $display_name,
        public string $network_id,
        public string $workspace_id
    ) {
    }
}
