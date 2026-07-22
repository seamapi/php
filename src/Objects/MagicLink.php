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
            created_at: $json->created_at ?? null,
            customer_key: $json->customer_key ?? null,
            expires_at: $json->expires_at ?? null,
            url: $json->url ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $customer_key,
        public string|null $expires_at,
        public string|null $url,
        public string|null $workspace_id,
    ) {}
}
