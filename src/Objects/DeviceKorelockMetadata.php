<?php

namespace Seam\Objects;

class DeviceKorelockMetadata
{
    public static function from_json(mixed $json): DeviceKorelockMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            firmware_version: $json->firmware_version ?? null,
            model_code: $json->model_code ?? null,
            serial_number: $json->serial_number ?? null,
            wifi_signal_strength: $json->wifi_signal_strength ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $device_name,
        public string|null $firmware_version,
        public string|null $model_code,
        public string|null $serial_number,
        public float|null $wifi_signal_strength,
    ) {}
}
