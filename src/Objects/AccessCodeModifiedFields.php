<?php

namespace Seam\Objects;

class AccessCodeModifiedFields
{
    public static function from_json(mixed $json): AccessCodeModifiedFields|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            field: $json->field,
            from: $json->from ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string $field,
        public string|null $from,
        public string|null $to,
    ) {}
}
