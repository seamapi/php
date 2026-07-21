<?php

namespace Seam\Objects;

class PhoneSessionVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_category: $json->door_category ?? null,
            door_name: $json->door_name ?? null,
            profiles: array_map(
                fn($p) => PhoneSessionProfiles::from_json($p),
                $json->profiles ?? [],
            ),
        );
    }

    public function __construct(
        public string|null $door_category,
        public string|null $door_name,
        public array|null $profiles,
    ) {}
}
