<?php

namespace Seam\Objects;

class UnmanagedDeviceBattery
{
    
    public static function from_json(mixed $json): UnmanagedDeviceBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            level: $json->level,
            status: $json->status,
        );
    }
  

    
    public function __construct(
        public int $level,
        public string $status,
    ) {
    }
  
}
