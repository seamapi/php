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
            noise_threshold_id: $json->noise_threshold_id,
            device_id: $json->device_id,
            name: $json->name,
            noise_threshold_nrs: $json->noise_threshold_nrs ?? null,
            starts_daily_at: $json->starts_daily_at,
            ends_daily_at: $json->ends_daily_at,
            noise_threshold_decibels: $json->noise_threshold_decibels,
        );
    }
  

    
    public function __construct(
        public string $noise_threshold_id,
        public string $device_id,
        public string $name,
        public float | null $noise_threshold_nrs,
        public string $starts_daily_at,
        public string $ends_daily_at,
        public float $noise_threshold_decibels,
    ) {
    }
  
}
