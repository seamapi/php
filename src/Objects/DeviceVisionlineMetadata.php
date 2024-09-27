<?php

namespace Seam\Objects;

class DeviceVisionlineMetadata
{
    public static function from_json(mixed $json): DeviceVisionlineMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(encoder_id: $json->encoder_id);
    }

    public function __construct(public string $encoder_id)
    {
    }
}
