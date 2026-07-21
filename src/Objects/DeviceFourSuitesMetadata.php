<?php

namespace Seam\Objects;

class DeviceFourSuitesMetadata
{
    public static function from_json(mixed $json): DeviceFourSuitesMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            reclose_delay_in_seconds: $json->reclose_delay_in_seconds ?? null,
        );
    }

    public function __construct(
        public float|null $device_id,
        public string|null $device_name,
        public float|null $reclose_delay_in_seconds,
    ) {}
}
