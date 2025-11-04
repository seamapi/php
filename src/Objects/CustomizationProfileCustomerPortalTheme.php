<?php

namespace Seam\Objects;

class CustomizationProfileCustomerPortalTheme
{
    public static function from_json(
        mixed $json,
    ): CustomizationProfileCustomerPortalTheme|null {
        if (!$json) {
            return null;
        }
        return new self(
            primary_color: $json->primary_color ?? null,
            primary_foreground_color: $json->primary_foreground_color ?? null,
            secondary_color: $json->secondary_color ?? null,
            secondary_foreground_color: $json->secondary_foreground_color ??
                null,
        );
    }

    public function __construct(
        public string|null $primary_color,
        public string|null $primary_foreground_color,
        public string|null $secondary_color,
        public string|null $secondary_foreground_color,
    ) {}
}
