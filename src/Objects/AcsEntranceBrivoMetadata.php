<?php

namespace Seam\Objects;

class AcsEntranceBrivoMetadata
{
    public static function from_json(mixed $json): AcsEntranceBrivoMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_point_id: $json->access_point_id ?? null,
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
        );
    }

    public function __construct(
        public string|null $access_point_id,
        public float|null $site_id,
        public string|null $site_name,
    ) {}
}
