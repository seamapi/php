<?php

namespace Seam\Objects;

class PhoneSessionHotekMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionHotekMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            common_area_name: $json->common_area_name ?? null,
            common_area_number: $json->common_area_number ?? null,
            room_number: $json->room_number ?? null,
        );
    }

    public function __construct(
        public string|null $common_area_name,
        public string|null $common_area_number,
        public string|null $room_number,
    ) {}
}
