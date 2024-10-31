<?php

namespace Seam\Objects;

class NoiseThreshold
{
    public static function from_json(mixed $json): NoiseThreshold|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            ends_daily_at: $json->ends_daily_at,
            name: $json->name,
            noise_threshold_decibels: $json->noise_threshold_decibels,
            noise_threshold_id: $json->noise_threshold_id,
            starts_daily_at: $json->starts_daily_at,
            noise_threshold_nrs: $json->noise_threshold_nrs ?? null
        );
    }

    public function __construct(
        public string $device_id,
        public string $ends_daily_at,
        public string $name,
        public float $noise_threshold_decibels,
        public string $noise_threshold_id,
        public string $starts_daily_at,
        public float|null $noise_threshold_nrs
    ) {
    }
}
