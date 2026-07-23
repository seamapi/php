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
            access_method_ids: $json->access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            failed_devices: array_map(
                fn($f) => AccessGrantFailedDevices::from_json($f),
                $json->failed_devices ?? [],
            ),
            message: $json->message ?? null,
            new_code: $json->new_code ?? null,
            original_code: $json->original_code ?? null,
            reason: $json->reason ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public array|null $access_method_ids,
        public string|null $created_at,
        public string|null $device_id,
        public array $failed_devices,
        public string|null $message,
        public string|null $new_code,
        public string|null $original_code,
        public string|null $reason,
        public string|null $warning_code,
    ) {}
}
