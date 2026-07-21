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
            bridge_id: $json->bridge_id ?? null,
            bridge_name: $json->bridge_name ?? null,
            device_id: $json->device_id ?? null,
            device_model: $json->device_model ?? null,
            device_name: $json->device_name ?? null,
            keypad_id: $json->keypad_id ?? null,
            serial_number: $json->serial_number ?? null,
        );
    }

    public function __construct(
        public float|null $bridge_id,
        public string|null $bridge_name,
        public float|null $device_id,
        public string|null $device_model,
        public string|null $device_name,
        public float|null $keypad_id,
        public string|null $serial_number,
    ) {}
}
