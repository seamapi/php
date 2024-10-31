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
            device_id: $json->device_id,
            device_info_model: $json->device_info_model,
            device_name: $json->device_name,
            product_model: $json->product_model,
            product_name: $json->product_name,
            product_type: $json->product_type,
            keypad_uuid: $json->keypad_uuid ?? null,
            locker_status_hardlock: $json->locker_status_hardlock ?? null
        );
    }

    public function __construct(
        public string $device_id,
        public string $device_info_model,
        public string $device_name,
        public string $product_model,
        public string $product_name,
        public string $product_type,
        public string|null $keypad_uuid,
        public float|null $locker_status_hardlock
    ) {
    }
}
