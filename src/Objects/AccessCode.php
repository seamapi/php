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
            type: $json->type,
            is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ?? null,
            access_code_id: $json->access_code_id,
            device_id: $json->device_id,
            name: $json->name ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            is_managed: $json->is_managed,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            status: $json->status,
            is_backup_access_code_available: $json->is_backup_access_code_available,
            is_backup: $json->is_backup ?? null,
            pulled_backup_access_code_id: $json->pulled_backup_access_code_id ?? null,
            is_external_modification_allowed: $json->is_external_modification_allowed,
            is_one_time_use: $json->is_one_time_use,
            is_offline_access_code: $json->is_offline_access_code,
        );
    }
  

    
    public function __construct(
        public string | null $common_code_key,
        public bool | null $is_scheduled_on_device,
        public string $type,
        public bool | null $is_waiting_for_code_assignment,
        public string $access_code_id,
        public string $device_id,
        public string | null $name,
        public string | null $code,
        public string $created_at,
        public mixed $errors,
        public mixed $warnings,
        public bool $is_managed,
        public string | null $starts_at,
        public string | null $ends_at,
        public string $status,
        public bool $is_backup_access_code_available,
        public bool | null $is_backup,
        public string | null $pulled_backup_access_code_id,
        public bool $is_external_modification_allowed,
        public bool $is_one_time_use,
        public bool $is_offline_access_code,
    ) {
    }
  
}
