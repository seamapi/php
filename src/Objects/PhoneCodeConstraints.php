<?php

namespace Seam\Objects;

class PhoneCodeConstraints
{
    
    public static function from_json(mixed $json): PhoneCodeConstraints|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            constraint_type: $json->constraint_type,
            min_length: $json->min_length ?? null,
            max_length: $json->max_length ?? null,
        );
    }
  

    
    public function __construct(
        public string $constraint_type,
        public float | null $min_length,
        public float | null $max_length,
    ) {
    }
  
}
