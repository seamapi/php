<?php

namespace Seam\Objects;

class DeviceSensiMetadata
{
    public static function from_json(mixed $json): DeviceSensiMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_name: $json->device_name,
            product_type: $json->product_type
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_name,
        public string $product_type
    ) {
    }
}
