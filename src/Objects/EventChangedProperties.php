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
            from: $json->from ?? null,
            property: $json->property ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string|null $from,
        public string|null $property,
        public string|null $to,
    ) {}
}
