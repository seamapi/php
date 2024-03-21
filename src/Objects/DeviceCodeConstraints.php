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
            min_length: $json->min_length ?? null,
            max_length: $json->max_length ?? null,
        );
    }
  

    
    public function __construct(
        public string $constraint_type,
        public int | null $min_length,
        public int | null $max_length,
    ) {
    }
  
}
