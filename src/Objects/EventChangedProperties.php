<?php

namespace Seam\Objects;

class EventChangedProperties
{
    public static function from_json(mixed $json): EventChangedProperties|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            property: $json->property,
            from: $json->from ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string $property,
        public string|null $from,
        public string|null $to,
    ) {}
}
