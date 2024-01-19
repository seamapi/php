<?php

namespace Seam\Objects;

class PhoneTtlockMetadata
{
    
    public static function from_json(mixed $json): PhoneTtlockMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            lock_id: $json->lock_id,
            lock_alias: $json->lock_alias,
        );
    }
  

    
    public function __construct(
        public float $lock_id,
        public string $lock_alias,
    ) {
    }
  
}
