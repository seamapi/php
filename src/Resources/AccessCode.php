<?php

namespace Seam\Resources;

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

class AccessCodeDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json,
    ): AccessCodeDormakabaOracodeMetadata|null {
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

class AccessCodeFrom
{
    public static function from_json(mixed $json): AccessCodeFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            code: $json->code ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $code,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}

class AccessCodeModifiedFields
{
    public static function from_json(mixed $json): AccessCodeModifiedFields|null
    {
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

class AccessCodePendingMutations
{
    public static function from_json(
        mixed $json,
    ): AccessCodePendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AccessCodeFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            scheduled_at: $json->scheduled_at ?? null,
            to: isset($json->to) ? AccessCodeTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public AccessCodeFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public string|null $scheduled_at,
        public AccessCodeTo|null $to,
    ) {}
}

class AccessCodeTo
{
    public static function from_json(mixed $json): AccessCodeTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            code: $json->code ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $code,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}

class AccessCodeWarnings
{
    public static function from_json(mixed $json): AccessCodeWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            change_type: $json->change_type ?? null,
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            modified_fields: array_map(
                fn($m) => AccessCodeModifiedFields::from_json($m),
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
