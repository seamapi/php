<?php

namespace Seam\Objects;

class AcsEntranceHotekMetadata
{
    public static function from_json(mixed $json): AcsEntranceHotekMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name,
            door_type: $json->door_type,
            room_number: $json->room_number,
        );
    }

    public function __construct(
        public string $display_name,
        public string $door_type,
        public string $room_number,
    ) {}
}
