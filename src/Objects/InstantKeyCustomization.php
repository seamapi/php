<?php

namespace Seam\Objects;

class InstantKeyCustomization
{
    public static function from_json(mixed $json): InstantKeyCustomization|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            logo_url: $json->logo_url ?? null,
            primary_color: $json->primary_color ?? null,
            secondary_color: $json->secondary_color ?? null,
        );
    }

    public function __construct(
        public string|null $logo_url,
        public string|null $primary_color,
        public string|null $secondary_color,
    ) {}
}
