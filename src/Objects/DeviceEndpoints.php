<?php

namespace Seam\Objects;

class DeviceEndpoints
{
    public static function from_json(mixed $json): DeviceEndpoints|null
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
