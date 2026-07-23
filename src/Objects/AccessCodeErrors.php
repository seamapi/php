<?php

namespace Seam\Objects;

class AccessCodeErrors
{
    public static function from_json(mixed $json): AccessCodeErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            change_type: $json->change_type ?? null,
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            is_access_code_error: $json->is_access_code_error ?? null,
            is_bridge_error: $json->is_bridge_error ?? null,
            is_connected_account_error: $json->is_connected_account_error ??
                null,
            is_device_error: $json->is_device_error ?? null,
            managed_access_code_id: $json->managed_access_code_id ?? null,
            message: $json->message ?? null,
            modified_fields: array_map(
                fn($m) => AccessCodeModifiedFields::from_json($m),
                $json->modified_fields ?? [],
            ),
            unmanaged_access_code_id: $json->unmanaged_access_code_id ?? null,
        );
    }

    public function __construct(
        public string|null $change_type,
        public string|null $created_at,
        public string|null $error_code,
        public bool|null $is_access_code_error,
        public bool|null $is_bridge_error,
        public bool|null $is_connected_account_error,
        public bool|null $is_device_error,
        public string|null $managed_access_code_id,
        public string|null $message,
        public array $modified_fields,
        public string|null $unmanaged_access_code_id,
    ) {}
}
