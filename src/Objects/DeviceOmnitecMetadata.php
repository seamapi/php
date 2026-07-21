<?php

namespace Seam\Objects;

class DeviceOmnitecMetadata
{
    public static function from_json(mixed $json): DeviceOmnitecMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            has_gateway: $json->has_gateway ?? null,
            lock_alias: $json->lock_alias ?? null,
            lock_id: $json->lock_id ?? null,
            lock_mac: $json->lock_mac ?? null,
            lock_name: $json->lock_name ?? null,
            timezone_raw_offset_ms: $json->timezone_raw_offset_ms ?? null,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public bool|null $has_gateway,
        public string|null $lock_alias,
        public float|null $lock_id,
        public string|null $lock_mac,
        public string|null $lock_name,
        public float|null $timezone_raw_offset_ms,
        public string|null $time_zone,
    ) {}
}
