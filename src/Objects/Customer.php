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
            created_at: $json->created_at,
            customer_key: $json->customer_key,
            workspace_id: $json->workspace_id,
        );
    }

    public function __construct(
        public string $created_at,
        public string $customer_key,
        public string $workspace_id,
    ) {}
}
