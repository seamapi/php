<?php

namespace Seam\Objects;

class Space
{
    public static function from_json(mixed $json): Space|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            display_name: $json->display_name,
            name: $json->name,
            space_id: $json->space_id,
            workspace_id: $json->workspace_id,
        );
    }

    public function __construct(
        public string $created_at,
        public string $display_name,
        public string $name,
        public string $space_id,
        public string $workspace_id,
    ) {}
}
