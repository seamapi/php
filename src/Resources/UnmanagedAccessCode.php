<?php

namespace Seam\Resources;

class UnmanagedAccessCode
{
    public static function from_json(mixed $json): UnmanagedAccessCode|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_code_id: $json->access_code_id ?? null,
            cannot_be_managed: $json->cannot_be_managed ?? null,
            cannot_delete_unmanaged_access_code: $json->cannot_delete_unmanaged_access_code ??
                null,
            code: $json->code ?? null,
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            dormakaba_oracode_metadata: isset($json->dormakaba_oracode_metadata)
                ? UnmanagedAccessCodeDormakabaOracodeMetadata::from_json(
                    $json->dormakaba_oracode_metadata,
                )
                : null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => UnmanagedAccessCodeErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_managed: $json->is_managed ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
            status: $json->status ?? null,
            type: $json->type ?? null,
            warnings: array_map(
                fn($w) => UnmanagedAccessCodeWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_code_id,
        public bool|null $cannot_be_managed,
        public bool|null $cannot_delete_unmanaged_access_code,
        public string|null $code,
        public string|null $created_at,
        public string|null $device_id,
        public UnmanagedAccessCodeDormakabaOracodeMetadata|null $dormakaba_oracode_metadata,
        public string|null $ends_at,
        public array $errors,
        public bool|null $is_managed,
        public string|null $name,
        public string|null $starts_at,
        public string|null $status,
        public string|null $type,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class UnmanagedAccessCodeDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeDormakabaOracodeMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            is_cancellable: $json->is_cancellable ?? null,
            is_early_checkin_able: $json->is_early_checkin_able ?? null,
            is_extendable: $json->is_extendable ?? null,
            is_overridable: $json->is_overridable ?? null,
            site_name: $json->site_name ?? null,
            stay_id: $json->stay_id ?? null,
            user_level_id: $json->user_level_id ?? null,
            user_level_name: $json->user_level_name ?? null,
        );
    }

    public function __construct(
        public bool|null $is_cancellable,
        public bool|null $is_early_checkin_able,
        public bool|null $is_extendable,
        public bool|null $is_overridable,
        public string|null $site_name,
        public float|null $stay_id,
        public string|null $user_level_id,
        public string|null $user_level_name,
    ) {}
}

class UnmanagedAccessCodeErrors
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeErrors|null {
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
                fn($m) => UnmanagedAccessCodeModifiedFields::from_json($m),
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

class UnmanagedAccessCodeModifiedFields
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeModifiedFields|null {
        if (!$json) {
            return null;
        }
        return new self(
            field: $json->field ?? null,
            from: $json->from ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string|null $field,
        public string|null $from,
        public string|null $to,
    ) {}
}

class UnmanagedAccessCodeWarnings
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeWarnings|null {
        if (!$json) {
            return null;
        }
        return new self(
            change_type: $json->change_type ?? null,
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            modified_fields: array_map(
                fn($m) => UnmanagedAccessCodeModifiedFields::from_json($m),
                $json->modified_fields ?? [],
            ),
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $change_type,
        public string|null $created_at,
        public string|null $message,
        public array $modified_fields,
        public string|null $warning_code,
    ) {}
}
