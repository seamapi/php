<?php

namespace Seam\Objects;

class PhoneSessionDormakabaAmbianceMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionDormakabaAmbianceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(access_point_name: $json->access_point_name);
    }

    public function __construct(public string $access_point_name) {}
}
