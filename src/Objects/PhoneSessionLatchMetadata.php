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
            accessibility_type: $json->accessibility_type ?? null,
            door_name: $json->door_name ?? null,
            door_type: $json->door_type ?? null,
            is_connected: $json->is_connected ?? null,
        );
    }

    public function __construct(
        public string|null $accessibility_type,
        public string|null $door_name,
        public string|null $door_type,
        public bool|null $is_connected,
    ) {}
}
