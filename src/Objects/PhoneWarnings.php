<?php

namespace Seam\Objects;

class PhoneWarnings
{
    
    public static function from_json(mixed $json): PhoneWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            warning_code: $json->warning_code,
            message: $json->message,
            created_at: $json->created_at ?? null,
        );
    }
  

    
    public function __construct(
        public string $warning_code,
        public string $message,
        public string | null $created_at,
    ) {
    }
  
}
