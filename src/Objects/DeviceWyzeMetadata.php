<?php

namespace Seam\Objects;

class DeviceWyzeMetadata
{
    public static function from_json(mixed $json): DeviceWyzeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_info_model: $json->device_info_model ?? null,
            device_name: $json->device_name ?? null,
            keypad_uuid: $json->keypad_uuid ?? null,
            locker_status_hardlock: $json->locker_status_hardlock ?? null,
            product_model: $json->product_model ?? null,
            product_name: $json->product_name ?? null,
            product_type: $json->product_type ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_info_model,
        public string|null $device_name,
        public string|null $keypad_uuid,
        public float|null $locker_status_hardlock,
        public string|null $product_model,
        public string|null $product_name,
        public string|null $product_type,
    ) {}
}
