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
            device_type: $json->device_type,
            serial_no: $json->serial_no
        );
    }

    public function __construct(
        public string $device_type,
        public string $serial_no
    ) {
    }
}
