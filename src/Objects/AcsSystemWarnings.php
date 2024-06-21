<?php

namespace Seam\Objects;

class AcsSystemWarnings
{
    public static function from_json(mixed $json): AcsSystemWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(created_at: $json->created_at ?? null);
    }

    public function __construct(public string|null $created_at)
    {
    }
}
