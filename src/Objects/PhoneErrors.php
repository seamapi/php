<?php

namespace Seam\Objects;

class PhoneErrors
{
    
    public static function from_json(mixed $json): PhoneErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            error_code: $json->error_code,
            message: $json->message,
        );
    }
  

    
    public function __construct(
        public string $error_code,
        public string $message,
    ) {
    }
  
}
