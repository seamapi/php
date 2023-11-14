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
            noise_threshold_id: $json->noise_threshold_id ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            noise_threshold_nrs: $json->noise_threshold_nrs ?? null,
            starts_daily_at: $json->starts_daily_at ?? null,
            ends_daily_at: $json->ends_daily_at ?? null,
            noise_threshold_decibels: $json->noise_threshold_decibels ?? null
        );
    }

    public function __construct(
        public string|null $noise_threshold_id,
        public string|null $device_id,
        public string|null $name,
        public float|null $noise_threshold_nrs,
        public string|null $starts_daily_at,
        public string|null $ends_daily_at,
        public float|null $noise_threshold_decibels
    ) {
    }
}
