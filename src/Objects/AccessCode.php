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
            access_code_id: $json->access_code_id,
            code: $json->code ?? null,
            common_code_key: $json->common_code_key ?? null,
            created_at: $json->created_at,
            device_id: $json->device_id,
            ends_at: $json->ends_at ?? null,
            errors: $json->errors,
            is_backup: $json->is_backup ?? null,
            is_backup_access_code_available: $json->is_backup_access_code_available,
            is_external_modification_allowed: $json->is_external_modification_allowed,
            is_managed: $json->is_managed,
            is_offline_access_code: $json->is_offline_access_code,
            is_one_time_use: $json->is_one_time_use,
            is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
            is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ??
                null,
            name: $json->name ?? null,
            pulled_backup_access_code_id: $json->pulled_backup_access_code_id ??
                null,
            starts_at: $json->starts_at ?? null,
            status: $json->status,
            type: $json->type,
            warnings: array_map(
                fn($w) => AccessCodeWarnings::from_json($w),
                $json->warnings ?? []
            )
        );
    }

    public function __construct(
        public string $access_code_id,
        public string|null $code,
        public string|null $common_code_key,
        public string $created_at,
        public string $device_id,
        public string|null $ends_at,
        public array $errors,
        public bool|null $is_backup,
        public bool $is_backup_access_code_available,
        public bool $is_external_modification_allowed,
        public bool $is_managed,
        public bool $is_offline_access_code,
        public bool $is_one_time_use,
        public bool|null $is_scheduled_on_device,
        public bool|null $is_waiting_for_code_assignment,
        public string|null $name,
        public string|null $pulled_backup_access_code_id,
        public string|null $starts_at,
        public string $status,
        public string $type,
        public array $warnings
    ) {
    }
}
