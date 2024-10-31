<?php

namespace Seam\Objects;

class DeviceTedeeMetadata
{
    public static function from_json(mixed $json): DeviceTedeeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            bridge_id: $json->bridge_id,
            bridge_name: $json->bridge_name,
            device_id: $json->device_id,
            device_model: $json->device_model,
            device_name: $json->device_name,
            serial_number: $json->serial_number,
            keypad_id: $json->keypad_id ?? null
        );
    }

    public function __construct(
        public float $bridge_id,
        public string $bridge_name,
        public float $device_id,
        public string $device_model,
        public string $device_name,
        public string $serial_number,
        public float|null $keypad_id
    ) {
    }
}
