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
            device_id: $json->device_id ?? null,
            ends_daily_at: $json->ends_daily_at ?? null,
            name: $json->name ?? null,
            noise_threshold_decibels: $json->noise_threshold_decibels ?? null,
            noise_threshold_id: $json->noise_threshold_id ?? null,
            noise_threshold_nrs: $json->noise_threshold_nrs ?? null,
            starts_daily_at: $json->starts_daily_at ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $ends_daily_at,
        public string|null $name,
        public float|null $noise_threshold_decibels,
        public string|null $noise_threshold_id,
        public float|null $noise_threshold_nrs,
        public string|null $starts_daily_at,
    ) {}
}
