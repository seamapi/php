<?php

namespace Seam\Objects;

class AcsSystemLocation
{
    public static function from_json(mixed $json): AcsSystemLocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(time_zone: $json->time_zone ?? null);
    }

    public function __construct(public string|null $time_zone)
    {
    }
}
