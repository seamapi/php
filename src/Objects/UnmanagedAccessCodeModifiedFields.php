<?php

namespace Seam\Objects;

class UnmanagedAccessCodeModifiedFields
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeModifiedFields|null {
        if (!$json) {
            return null;
        }
        return new self(
            field: $json->field ?? null,
            from: $json->from ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string|null $field,
        public string|null $from,
        public string|null $to,
    ) {}
}
