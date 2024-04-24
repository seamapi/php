<?php

namespace Seam\Objects;

class DeviceAppearance
{
    public static function from_json(mixed $json): DeviceAppearance|null
    {
        if (!$json) {
            return null;
        }
        return new self(name: $json->name);
    }

    public function __construct(public string $name)
    {
    }
}
