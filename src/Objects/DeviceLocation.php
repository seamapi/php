<?php

namespace Seam\Objects;

class DeviceLocation
{
    public static function from_json(mixed $json): DeviceLocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            location_name: $json->location_name ?? null,
            timezone: $json->timezone ?? null
        );
    }

    public function __construct(
        public string|null $location_name,
        public string|null $timezone
    ) {
    }
}
