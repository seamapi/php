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
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            is_bridge_error: $json->is_bridge_error ?? null,
            is_connected_account_error: $json->is_connected_account_error ??
                null,
            is_device_error: $json->is_device_error ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public bool|null $is_bridge_error,
        public bool|null $is_connected_account_error,
        public bool|null $is_device_error,
        public string|null $message,
    ) {}
}
