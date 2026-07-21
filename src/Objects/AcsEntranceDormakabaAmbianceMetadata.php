<?php

namespace Seam\Objects;

class AcsEntranceDormakabaAmbianceMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceDormakabaAmbianceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(access_point_name: $json->access_point_name ?? null);
    }

    public function __construct(public string|null $access_point_name) {}
}
