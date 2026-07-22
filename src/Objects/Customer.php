<?php

namespace Seam\Objects;

class Customer
{
    public static function from_json(mixed $json): Customer|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            customer_key: $json->customer_key ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $customer_key,
        public string|null $workspace_id,
    ) {}
}
