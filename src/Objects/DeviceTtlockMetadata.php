<?php

namespace Seam\Objects;

class DeviceTtlockMetadata
{
    public static function from_json(mixed $json): DeviceTtlockMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(lock_alias: $json->lock_alias, lock_id: $json->lock_id);
    }

    public function __construct(
        public string $lock_alias,
        public float $lock_id
    ) {
    }
}
