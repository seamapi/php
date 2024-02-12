<?php

namespace Seam\Objects;

class DeviceTtlockMetadata
{
    
    public static function from_json(mixed $json): DeviceTtlockMetadata|null
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
        public int $lock_id,
        public string $lock_alias,
    ) {
    }
  
}
