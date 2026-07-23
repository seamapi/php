<?php

namespace Seam\Objects;

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
