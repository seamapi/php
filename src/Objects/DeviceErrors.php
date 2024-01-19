<?php

namespace Seam\Objects;

class DeviceErrors
{
    
    public static function from_json(mixed $json): DeviceErrors|null
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
