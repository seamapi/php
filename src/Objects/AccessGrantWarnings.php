<?php

namespace Seam\Objects;

class AccessGrantWarnings
{
    public static function from_json(mixed $json): AccessGrantWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code,
            access_method_ids: $json->access_method_ids ?? null,
            device_id: $json->device_id ?? null,
            failed_devices: array_map(
                fn($f) => AccessGrantFailedDevices::from_json($f),
                $json->failed_devices ?? [],
            ),
            new_code: $json->new_code ?? null,
            original_code: $json->original_code ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code,
        public array|null $access_method_ids,
        public string|null $device_id,
        public array|null $failed_devices,
        public string|null $new_code,
        public string|null $original_code,
    ) {}
}
