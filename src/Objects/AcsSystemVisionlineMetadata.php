<?php

namespace Seam\Objects;

class AcsSystemVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsSystemVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            lan_address: $json->lan_address ?? null,
            mobile_access_uuid: $json->mobile_access_uuid ?? null,
            system_id: $json->system_id ?? null,
        );
    }

    public function __construct(
        public string|null $lan_address,
        public string|null $mobile_access_uuid,
        public string|null $system_id,
    ) {}
}
