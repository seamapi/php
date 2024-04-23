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
            device_name: $json->device_name,
            ecobee_device_id: $json->ecobee_device_id
        );
    }

    public function __construct(
        public string $device_name,
        public string $ecobee_device_id
    ) {
    }
}
