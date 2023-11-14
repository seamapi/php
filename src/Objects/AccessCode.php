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
            common_code_key: $json->common_code_key ?? null,
            is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
            type: $json->type ?? null,
            is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ??
                null,
            access_code_id: $json->access_code_id ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at ?? null,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            is_managed: $json->is_managed ?? null,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            status: $json->status ?? null,
            is_backup_access_code_available: $json->is_backup_access_code_available ??
                null,
            is_backup: $json->is_backup ?? null,
            pulled_backup_access_code_id: $json->pulled_backup_access_code_id ??
                null,
            is_external_modification_allowed: $json->is_external_modification_allowed ??
                null,
            is_one_time_use: $json->is_one_time_use ?? null,
            is_offline_access_code: $json->is_offline_access_code ?? null
        );
    }

    public function __construct(
        public string|null $common_code_key,
        public bool|null $is_scheduled_on_device,
        public string|null $type,
        public bool|null $is_waiting_for_code_assignment,
        public string|null $access_code_id,
        public string|null $device_id,
        public string|null $name,
        public string|null $code,
        public string|null $created_at,
        public mixed $errors,
        public mixed $warnings,
        public bool|null $is_managed,
        public string|null $starts_at,
        public string|null $ends_at,
        public string|null $status,
        public bool|null $is_backup_access_code_available,
        public bool|null $is_backup,
        public string|null $pulled_backup_access_code_id,
        public bool|null $is_external_modification_allowed,
        public bool|null $is_one_time_use,
        public bool|null $is_offline_access_code
    ) {
    }
}
