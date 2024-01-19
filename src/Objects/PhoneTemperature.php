<?php

namespace Seam\Objects;

class PhoneTemperature
{
    
    public static function from_json(mixed $json): PhoneTemperature|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            time: $json->time,
            value: $json->value,
        );
    }
  

    
    public function __construct(
        public string $time,
        public float $value,
    ) {
    }
  
}
