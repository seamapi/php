<?php

namespace Seam\Objects;

class SpaceGeolocation
{
    public static function from_json(mixed $json): SpaceGeolocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            latitude: $json->latitude ?? null,
            longitude: $json->longitude ?? null,
        );
    }

    public function __construct(
        public float|null $latitude,
        public float|null $longitude,
    ) {}
}
