<?php

namespace Seam\Objects;

class PhoneEndpoints
{
    public static function from_json(mixed $json): PhoneEndpoints|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            endpoint_id: $json->endpoint_id ?? null,
            is_active: $json->is_active ?? null,
        );
    }

    public function __construct(
        public string|null $endpoint_id,
        public bool|null $is_active,
    ) {}
}
