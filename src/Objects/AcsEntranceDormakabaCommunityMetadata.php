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
            access_point_name: $json->access_point_name,
            access_point_profile: $json->access_point_profile,
        );
    }

    public function __construct(
        public string $access_point_name,
        public string $access_point_profile,
    ) {}
}
