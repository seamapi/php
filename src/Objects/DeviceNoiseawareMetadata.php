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
            device_id: $json->device_id,
            device_model: $json->device_model,
            device_name: $json->device_name,
            noise_level_decibel: $json->noise_level_decibel,
            noise_level_nrs: $json->noise_level_nrs
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_model,
        public string $device_name,
        public float $noise_level_decibel,
        public float $noise_level_nrs
    ) {
    }
}
