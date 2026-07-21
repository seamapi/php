<?php

namespace Seam\Objects;

class DeviceEcobeeMetadata
{
    public static function from_json(mixed $json): DeviceEcobeeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name ?? null,
            ecobee_device_id: $json->ecobee_device_id ?? null,
        );
    }

    public function __construct(
        public string|null $device_name,
        public string|null $ecobee_device_id,
    ) {}
}
