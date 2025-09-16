<?php

namespace Seam\Objects;

class PhoneSessionDormakabaCommunityMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionDormakabaCommunityMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(access_point_profile: $json->access_point_profile);
    }

    public function __construct(public string $access_point_profile) {}
}
