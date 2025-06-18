<?php

namespace Seam\Objects;

class LocationGeolocation
{
    public static function from_json(mixed $json): LocationGeolocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(latitude: $json->latitude, longitude: $json->longitude);
    }

    public function __construct(public float $latitude, public float $longitude)
    {
    }
}
