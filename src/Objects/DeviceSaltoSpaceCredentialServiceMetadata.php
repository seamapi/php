<?php

namespace Seam\Objects;

class DeviceSaltoSpaceCredentialServiceMetadata
{
    public static function from_json(
        mixed $json
    ): DeviceSaltoSpaceCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(has_active_phone: $json->has_active_phone);
    }

    public function __construct(public bool $has_active_phone)
    {
    }
}
