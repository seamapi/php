<?php

namespace Seam\Objects;

class DeviceSound
{
    public static function from_json(mixed $json): DeviceSound|null
    {
        if (!$json) {
            return null;
        }
        return new self(time: $json->time ?? null, value: $json->value ?? null);
    }

    public function __construct(
        public string|null $time,
        public float|null $value,
    ) {}
}
