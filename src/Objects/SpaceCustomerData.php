<?php

namespace Seam\Objects;

class SpaceCustomerData
{
    public static function from_json(mixed $json): SpaceCustomerData|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            default_checkin_time: $json->default_checkin_time ?? null,
            default_checkout_time: $json->default_checkout_time ?? null,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $default_checkin_time,
        public string|null $default_checkout_time,
        public string|null $time_zone,
    ) {}
}
