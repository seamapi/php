<?php

namespace Seam\Objects;

class AcsSystemVisionlineMetadata
{
    public static function from_json(
        mixed $json
    ): AcsSystemVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            lan_address: $json->lan_address,
            mobile_access_uuid: $json->mobile_access_uuid,
            system_id: $json->system_id
        );
    }

    public function __construct(
        public string $lan_address,
        public string $mobile_access_uuid,
        public string $system_id
    ) {
    }
}
