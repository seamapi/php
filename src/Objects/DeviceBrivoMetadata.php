<?php

namespace Seam\Objects;

class DeviceBrivoMetadata
{
    public static function from_json(mixed $json): DeviceBrivoMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(device_name: $json->device_name);
    }

    public function __construct(public string $device_name)
    {
    }
}
