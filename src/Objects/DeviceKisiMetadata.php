<?php

namespace Seam\Objects;

class DeviceKisiMetadata
{
    public static function from_json(mixed $json): DeviceKisiMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            description: $json->description ?? null,
            lock_id: $json->lock_id ?? null,
            lock_name: $json->lock_name ?? null,
            place_name: $json->place_name ?? null,
        );
    }

    public function __construct(
        public string|null $description,
        public float|null $lock_id,
        public string|null $lock_name,
        public string|null $place_name,
    ) {}
}
