<?php

namespace Seam\Objects;

class AcsEntranceLatchMetadata
{
    
    public static function from_json(mixed $json): AcsEntranceLatchMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            accessibility_type: $json->accessibility_type,
            name: $json->name,
            type: $json->type,
            is_connected: $json->is_connected,
        );
    }
  

    
    public function __construct(
        public string $accessibility_type,
        public string $name,
        public string $type,
        public bool $is_connected,
    ) {
    }
  
}
