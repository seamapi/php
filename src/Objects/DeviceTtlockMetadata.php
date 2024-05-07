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
            lock_alias: $json->lock_alias,
            lock_id: $json->lock_id
        );
    }

    public function __construct(
        public string $feature_value,
        public string $lock_alias,
        public float $lock_id
    ) {
    }
}
