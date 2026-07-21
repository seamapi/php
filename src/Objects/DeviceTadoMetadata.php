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
            device_type: $json->device_type ?? null,
            serial_no: $json->serial_no ?? null,
        );
    }

    public function __construct(
        public string|null $device_type,
        public string|null $serial_no,
    ) {}
}
