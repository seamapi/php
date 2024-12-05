<?php

namespace Seam\Objects;

class DeviceTadoMetadata
{
    public static function from_json(mixed $json): DeviceTadoMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name,
            device_type: $json->device_type,
            serial_number: $json->serial_number
        );
    }

    public function __construct(
        public string $device_name,
        public string $device_type,
        public string $serial_number
    ) {
    }
}
