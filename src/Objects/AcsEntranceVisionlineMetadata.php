<?php

namespace Seam\Objects;

class AcsEntranceVisionlineMetadata
{
    
    public static function from_json(mixed $json): AcsEntranceVisionlineMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            door_name: $json->door_name,
            door_category: $json->door_category,
            profiles: array_map(
          fn ($p) => AcsEntranceProfiles::from_json($p),
          $json->profiles ?? []
        ),
        );
    }
  

    
    public function __construct(
        public string $door_name,
        public string $door_category,
        public array | null $profiles,
    ) {
    }
  
}
