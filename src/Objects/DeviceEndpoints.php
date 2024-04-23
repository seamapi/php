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
            endpoint_id: $json->endpoint_id,
            is_active: $json->is_active
        );
    }

    public function __construct(
        public string $endpoint_id,
        public bool $is_active
    ) {
    }
}
