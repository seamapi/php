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
            description: $json->description,
            service: $json->service,
            status: $json->status
        );
    }

    public function __construct(
        public string $description,
        public string $service,
        public string $status
    ) {}
}
