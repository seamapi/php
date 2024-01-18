<?php

namespace Seam\Objects;

class PhoneKeypadBattery
{
    
    public static function from_json(mixed $json): PhoneKeypadBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            level: $json->level,
        );
    }
  

    
    public function __construct(
        public float $level,
    ) {
    }
  
}
