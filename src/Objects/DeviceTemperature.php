<?php

namespace Seam\Objects;

class DeviceTemperature
{
    public static function from_json(mixed $json): DeviceTemperature|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time, value: $json->value);
    }

    public function __construct(public string $time, public float $value)
    {
    }
}
