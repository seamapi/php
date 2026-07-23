<?php

namespace Seam\Objects;

class AccessCodeTo
{
    public static function from_json(mixed $json): AccessCodeTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            code: $json->code ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $code,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}
