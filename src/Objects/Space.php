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
            acs_entrance_count: $json->acs_entrance_count,
            created_at: $json->created_at,
            device_count: $json->device_count,
            display_name: $json->display_name,
            name: $json->name,
            space_id: $json->space_id,
            workspace_id: $json->workspace_id,
            space_key: $json->space_key ?? null
        );
    }

    public function __construct(
        public float $acs_entrance_count,
        public string $created_at,
        public float $device_count,
        public string $display_name,
        public string $name,
        public string $space_id,
        public string $workspace_id,
        public string|null $space_key
    ) {}
}
