<?php

namespace Seam\Objects;

class DeviceBrivoMetadata
{
    public static function from_json(mixed $json): DeviceBrivoMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            activation_enabled: $json->activation_enabled ?? null,
            device_name: $json->device_name ?? null,
        );
    }

    public function __construct(
        public bool|null $activation_enabled,
        public string|null $device_name,
    ) {}
}
