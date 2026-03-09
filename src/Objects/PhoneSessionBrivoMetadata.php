<?php

namespace Seam\Objects;

class PhoneSessionBrivoMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionBrivoMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_point_id: $json->access_point_id,
            site_id: $json->site_id,
            site_name: $json->site_name,
        );
    }

    public function __construct(
        public string $access_point_id,
        public float $site_id,
        public string $site_name,
    ) {}
}
