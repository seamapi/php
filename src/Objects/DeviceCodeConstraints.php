<?php

namespace Seam\Objects;

class DeviceCodeConstraints
{
    public static function from_json(mixed $json): DeviceCodeConstraints|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            constraint_type: $json->constraint_type,
            max_length: $json->max_length ?? null,
            min_length: $json->min_length ?? null
        );
    }

    public function __construct(
        public string $constraint_type,
        public float|null $max_length,
        public float|null $min_length
    ) {
    }
}
