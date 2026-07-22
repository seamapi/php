<?php

namespace Seam\Objects;

class UnmanagedDeviceBattery
{
    public static function from_json(mixed $json): UnmanagedDeviceBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level ?? null);
    }

    public function __construct(public float|null $level) {}
}
