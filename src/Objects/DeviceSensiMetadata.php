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
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            dual_setpoints_not_supported: $json->dual_setpoints_not_supported ??
                null,
            product_type: $json->product_type ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public bool|null $dual_setpoints_not_supported,
        public string|null $product_type,
    ) {}
}
