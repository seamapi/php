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
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            created_at: $json->created_at ?? null,
            type: $json->type ?? null,
            code: $json->code ?? null,
            status: $json->status ?? null,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => SeamError::from_json($e),
                $json->errors ?? []
            ),
            warnings: array_map(
                fn($e) => SeamWarning::from_json($e),
                $json->warnings ?? []
            ),
            is_managed: $json->is_managed ?? null,
            common_code_key: $json->common_code_key ?? null,
            is_waiting_for_code_assignment: $json->is_waiting_for_code_assignment ?? null,
            is_scheduled_on_device: $json->is_scheduled_on_device ?? null,
            pulled_backup_access_code_id: $json->pulled_backup_access_code_id ?? null,
            is_backup_access_code_available: $json->is_backup_access_code_available ?? null,
            is_backup: $json->is_backup ?? null,
        );
    }

    public function __construct(
        public string $access_code_id,
        public string $device_id,
        public string | null $name,

        /* "time_bound" or "ongoing" */
        public string $type,

        /*
         * The status of an access code on the device.
         * unset -> setting -> set -> unset OR "unknown" if the account is disconnected
         */
        public string $status,

        /* In ISO8601 timestamp format, only for time_bound codes */
        public string|null $starts_at,

        /* In ISO8601 timestamp format, only for time_bound codes */
        public string|null $ends_at,

        /*
         * The 4-8 digit code assigned to the device, note that this isn't always
         * immediately available after creating the access code.
         */
        public string|null $code,
        public string $created_at,

        /* @var SeamError[] */
        public array $errors,

        /* @var SeamWarning[] */
        public array $warnings,
        public bool|null $is_managed,
        public string|null $common_code_key,
        public bool|null $is_waiting_for_code_assignment,
        public bool|null $is_scheduled_on_device,
        public string|null $pulled_backup_access_code_id,
        public bool|null $is_backup_access_code_available,
        public bool|null $is_backup
        ) {
    }
}
