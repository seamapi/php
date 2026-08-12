<?php

namespace Seam\Resources {
    /**
     * Represents a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors). Thresholds represent the limits of noise tolerated at a property, which can be customized for each hour of the day. Each device has its own default thresholds, but you can use the Seam API to modify them.
     */
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
                noise_threshold_decibels: $json->noise_threshold_decibels ??
                    null,
                noise_threshold_id: $json->noise_threshold_id ?? null,
                noise_threshold_nrs: $json->noise_threshold_nrs ?? null,
                starts_daily_at: $json->starts_daily_at ?? null,
            );
        }

        public function __construct(
            /**
             * Unique identifier for the device that contains the noise threshold.
             */
            public string|null $device_id,
            /**
             * Time at which the noise threshold should become inactive daily.
             */
            public string|null $ends_daily_at,
            /**
             * Name of the noise threshold.
             */
            public string|null $name,
            /**
             * Noise level in decibels for the noise threshold.
             */
            public float|null $noise_threshold_decibels,
            /**
             * Unique identifier for the noise threshold.
             */
            public string|null $noise_threshold_id,
            /**
             * Noise level in Noiseaware Noise Risk Score (NRS) for the noise threshold. This parameter is only relevant for [Noiseaware sensors](https://docs.seam.co/device-and-system-integration-guides/noiseaware-sensors).
             */
            public float|null $noise_threshold_nrs,
            /**
             * Time at which the noise threshold should become active daily.
             */
            public string|null $starts_daily_at,
        ) {}
    }
}
