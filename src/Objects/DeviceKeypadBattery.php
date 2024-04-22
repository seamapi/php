<?php

namespace Seam\Objects;

class DeviceKeypadBattery
{
    public static function from_json(mixed $json): DeviceKeypadBattery|null
    {
        if (!$json) {
            return null;
        }
        return new self(level: $json->level);
    }

    public function __construct(public float $level)
    {
    }
}
