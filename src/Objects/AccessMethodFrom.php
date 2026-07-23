<?php

namespace Seam\Objects;

class AccessMethodFrom
{
    public static function from_json(mixed $json): AccessMethodFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_ids: $json->device_ids ?? null,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public array|null $device_ids,
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}
