<?php

namespace Seam\Objects;

class DevicePeriods
{
    public static function from_json(mixed $json): DevicePeriods|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            climate_preset_key: $json->climate_preset_key,
            starts_at_time: $json->starts_at_time
        );
    }

    public function __construct(
        public string $climate_preset_key,
        public string $starts_at_time
    ) {
    }
}
