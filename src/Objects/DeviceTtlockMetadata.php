<?php

namespace Seam\Objects;

class DeviceTtlockMetadata
{
    public static function from_json(mixed $json): DeviceTtlockMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            feature_value: $json->feature_value ?? null,
            features: isset($json->features)
                ? DeviceFeatures::from_json($json->features)
                : null,
            has_gateway: $json->has_gateway ?? null,
            lock_alias: $json->lock_alias ?? null,
            lock_id: $json->lock_id ?? null,
            wireless_keypads: array_map(
                fn($w) => DeviceWirelessKeypads::from_json($w),
                $json->wireless_keypads ?? [],
            ),
            timezone_raw_offset_ms: $json->timezone_raw_offset_ms ?? null,
        );
    }

    public function __construct(
        public string|null $feature_value,
        public DeviceFeatures|null $features,
        public bool|null $has_gateway,
        public string|null $lock_alias,
        public float|null $lock_id,
        public array|null $wireless_keypads,
        public float|null $timezone_raw_offset_ms,
    ) {}
}
