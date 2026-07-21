<?php

namespace Seam\Objects;

class AcsEntranceDormakabaCommunityMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceDormakabaCommunityMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_point_profile: $json->access_point_profile ?? null,
        );
    }

    public function __construct(public string|null $access_point_profile) {}
}
