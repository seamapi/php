<?php

namespace Seam\Objects;

class ServiceHealth
{
    public static function from_json(mixed $json): ServiceHealth|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            service: $json->service ?? null,
            status: $json->status ?? null,
            description: $json->description ?? null
        );
    }

    public function __construct(
        public string|null $service,
        public string|null $status,
        public string|null $description
    ) {
    }
}
