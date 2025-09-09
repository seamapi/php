<?php

namespace Seam\Objects;

class PhoneSessionSaltoSpaceMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionSaltoSpaceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_description: $json->door_description ?? null,
            door_id: $json->door_id ?? null,
            door_name: $json->door_name ?? null,
            ext_door_id: $json->ext_door_id ?? null,
            room_description: $json->room_description ?? null,
            room_name: $json->room_name ?? null,
        );
    }

    public function __construct(
        public string|null $door_description,
        public string|null $door_id,
        public string|null $door_name,
        public string|null $ext_door_id,
        public string|null $room_description,
        public string|null $room_name,
    ) {}
}
