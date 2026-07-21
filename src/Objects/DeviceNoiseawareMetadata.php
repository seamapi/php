<?php

namespace Seam\Objects;

class DeviceNoiseawareMetadata
{
    public static function from_json(mixed $json): DeviceNoiseawareMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_model: $json->device_model ?? null,
            device_name: $json->device_name ?? null,
            noise_level_decibel: $json->noise_level_decibel ?? null,
            noise_level_nrs: $json->noise_level_nrs ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_model,
        public string|null $device_name,
        public float|null $noise_level_decibel,
        public float|null $noise_level_nrs,
    ) {}
}
