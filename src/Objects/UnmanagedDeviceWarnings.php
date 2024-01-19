<?php

namespace Seam\Objects;

class UnmanagedDeviceWarnings
{
    
    public static function from_json(mixed $json): UnmanagedDeviceWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            warning_code: $json->warning_code,
            message: $json->message,
        );
    }
  

    
    public function __construct(
        public string $warning_code,
        public string $message,
    ) {
    }
  
}
