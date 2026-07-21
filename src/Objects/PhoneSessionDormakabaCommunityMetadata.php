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
        return new self(
            access_point_profile: $json->access_point_profile ?? null,
        );
    }

    public function __construct(public string|null $access_point_profile) {}
}
