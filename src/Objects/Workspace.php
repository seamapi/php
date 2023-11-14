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
            workspace_id: $json->workspace_id ?? null,
            name: $json->name ?? null,
            is_sandbox: $json->is_sandbox ?? null,
            connect_partner_name: $json->connect_partner_name ?? null
        );
    }

    public function __construct(
        public string|null $workspace_id,
        public string|null $name,
        public bool|null $is_sandbox,
        public string|null $connect_partner_name
    ) {
    }
}
