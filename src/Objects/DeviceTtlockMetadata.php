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
            feature_value: $json->feature_value,
            features: DeviceFeatures::from_json($json->features),
            has_gateway: $json->has_gateway ?? null,
            lock_alias: $json->lock_alias,
            lock_id: $json->lock_id,
            wireless_keypads: array_map(
                fn($w) => DeviceWirelessKeypads::from_json($w),
                $json->wireless_keypads ?? []
            )
        );
    }

    public function __construct(
        public string $feature_value,
        public DeviceFeatures $features,
        public bool|null $has_gateway,
        public string $lock_alias,
        public float $lock_id,
        public array|null $wireless_keypads
    ) {
    }
}
