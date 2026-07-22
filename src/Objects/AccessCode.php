<?php

namespace Seam\Objects;

class AccessCode
{
    public static function from_json(mixed $json): AccessCode|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_code_id: $json->access_code_id ?? null,
            code: $json->code ?? null,
            common_code_key: $json->common_code_key ?? null,
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            dormakaba_oracode_metadata: isset($json->dormakaba_oracode_metadata)
                ? AccessCodeDormakabaOracodeMetadata::from_json(
                    $json->dormakaba_oracode_metadata,
                )
                : null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => AccessCodeErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_backup: $json->is_backup ?? null,
            is_backup_access_code_available: $json->is_backup_access_code_available ??
                null,
            is_external_modification_allowed: $json->is_external_modification_allowed ??
                null,
            is_managed: $json->is_managed ?? null,
            is_offline_access_code: $json->is_offline_access_code ?? null,
            is_one_time_use: $json->is_one_time_use ?? null,
            is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
            is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ??
                null,
            name: $json->name ?? null,
            pending_mutations: array_map(
                fn($p) => AccessCodePendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            pulled_backup_access_code_id: $json->pulled_backup_access_code_id ??
                null,
            starts_at: $json->starts_at ?? null,
            status: $json->status ?? null,
            type: $json->type ?? null,
            warnings: array_map(
                fn($w) => AccessCodeWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_code_id,
        public string|null $code,
        public string|null $common_code_key,
        public string|null $created_at,
        public string|null $device_id,
        public AccessCodeDormakabaOracodeMetadata|null $dormakaba_oracode_metadata,
        public string|null $ends_at,
        public array $errors,
        public bool|null $is_backup,
        public bool|null $is_backup_access_code_available,
        public bool|null $is_external_modification_allowed,
        public bool|null $is_managed,
        public bool|null $is_offline_access_code,
        public bool|null $is_one_time_use,
        public bool|null $is_scheduled_on_device,
        public bool|null $is_waiting_for_code_assignment,
        public string|null $name,
        public array $pending_mutations,
        public string|null $pulled_backup_access_code_id,
        public string|null $starts_at,
        public string|null $status,
        public string|null $type,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
