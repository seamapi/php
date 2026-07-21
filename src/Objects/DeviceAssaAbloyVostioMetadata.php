<?php

namespace Seam\Objects;

class DeviceAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceAssaAbloyVostioMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(encoder_name: $json->encoder_name ?? null);
    }

    public function __construct(public string|null $encoder_name) {}
}
