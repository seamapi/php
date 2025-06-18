<?php

namespace Seam\Objects;

class MagicLink
{
    public static function from_json(mixed $json): MagicLink|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            building_block_type: $json->building_block_type,
            created_at: $json->created_at,
            customer_key: $json->customer_key,
            expires_at: $json->expires_at,
            url: $json->url,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $building_block_type,
        public string $created_at,
        public string $customer_key,
        public string $expires_at,
        public string $url,
        public string $workspace_id
    ) {
    }
}
