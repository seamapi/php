<?php

namespace Seam\Objects;

class PhoneSessionProfiles
{
    public static function from_json(mixed $json): PhoneSessionProfiles|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            visionline_door_profile_id: $json->visionline_door_profile_id,
            visionline_door_profile_type: $json->visionline_door_profile_type,
        );
    }

    public function __construct(
        public string $visionline_door_profile_id,
        public string $visionline_door_profile_type,
    ) {}
}
