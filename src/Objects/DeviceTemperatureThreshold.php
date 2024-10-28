<?php

namespace Seam\Objects;

class DeviceTemperatureThreshold
{
    public static function from_json(
        mixed $json
    ): DeviceTemperatureThreshold|null {
        if (!$json) {
            return null;
        }
        return new self(
            lower_limit_celsius: $json->lower_limit_celsius ?? null,
            lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
            upper_limit_celsius: $json->upper_limit_celsius ?? null,
            upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null
        );
    }

    public function __construct(
        public float|null $lower_limit_celsius,
        public float|null $lower_limit_fahrenheit,
        public float|null $upper_limit_celsius,
        public float|null $upper_limit_fahrenheit
    ) {
    }
}
