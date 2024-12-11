<?php

namespace Seam\Objects;

class AcsEntranceSaltoSpaceMetadata
{
    public static function from_json(
        mixed $json
    ): AcsEntranceSaltoSpaceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_name: $json->door_name,
            ext_door_id: $json->ext_door_id,
            door_description: $json->door_description ?? null
        );
    }

    public function __construct(
        public string $door_name,
        public string $ext_door_id,
        public string|null $door_description
    ) {
    }
}
