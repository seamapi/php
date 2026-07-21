<?php

namespace Seam\Objects;

class EventTo
{
    public static function from_json(mixed $json): EventTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}
