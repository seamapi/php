<?php

namespace Seam\Objects;

class PhoneSessionLatchMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionLatchMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            accessibility_type: $json->accessibility_type,
            door_name: $json->door_name,
            door_type: $json->door_type,
            is_connected: $json->is_connected,
        );
    }

    public function __construct(
        public string $accessibility_type,
        public string $door_name,
        public string $door_type,
        public bool $is_connected,
    ) {}
}
