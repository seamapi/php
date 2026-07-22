<?php

namespace Seam\Objects;

class DeviceBattery
{
    public static function from_json(mixed $json): DeviceBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level ?? null);
    }

    public function __construct(public float|null $level) {}
}
