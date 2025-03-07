<?php

namespace Seam\Objects;

class UnmanagedDeviceErrors
{
    public static function from_json(mixed $json): UnmanagedDeviceErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            error_code: $json->error_code,
            message: $json->message,
            created_at: $json->created_at ?? null,
            is_connected_account_error: $json->is_connected_account_error ??
                null,
            is_device_error: $json->is_device_error ?? null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? UnmanagedDeviceSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
                )
                : null
        );
    }

    public function __construct(
        public string $error_code,
        public string $message,
        public string|null $created_at,
        public bool|null $is_connected_account_error,
        public bool|null $is_device_error,
        public UnmanagedDeviceSaltoKsMetadata|null $salto_ks_metadata
    ) {
    }
}
