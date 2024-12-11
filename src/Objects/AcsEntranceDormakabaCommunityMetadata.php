<?php

namespace Seam\Objects;

class AcsEntranceDormakabaCommunityMetadata
{
    public static function from_json(
        mixed $json
    ): AcsEntranceDormakabaCommunityMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_point_name: $json->access_point_name,
            common_area_number: $json->common_area_number ?? null,
            inner_access_points_names: $json->inner_access_points_names ?? null
        );
    }

    public function __construct(
        public string $access_point_name,
        public float|null $common_area_number,
        public array|null $inner_access_points_names
    ) {
    }
}
