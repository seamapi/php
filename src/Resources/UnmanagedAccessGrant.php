<?php

namespace Seam\Resources;

class UnmanagedAccessGrant
{
    public static function from_json(mixed $json): UnmanagedAccessGrant|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_grant_id: $json->access_grant_id ?? null,
            access_method_ids: $json->access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => UnmanagedAccessGrantErrors::from_json($e),
                $json->errors ?? [],
            ),
            location_ids: $json->location_ids ?? null,
            name: $json->name ?? null,
            pending_mutations: array_map(
                fn($p) => UnmanagedAccessGrantPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            requested_access_methods: array_map(
                fn($r) => UnmanagedAccessGrantRequestedAccessMethods::from_json(
                    $r,
                ),
                $json->requested_access_methods ?? [],
            ),
            reservation_key: $json->reservation_key ?? null,
            space_ids: $json->space_ids ?? null,
            starts_at: $json->starts_at ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            warnings: array_map(
                fn($w) => UnmanagedAccessGrantWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_grant_id,
        public array|null $access_method_ids,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $ends_at,
        public array $errors,
        public array|null $location_ids,
        public string|null $name,
        public array $pending_mutations,
        public array $requested_access_methods,
        public string|null $reservation_key,
        public array|null $space_ids,
        public string|null $starts_at,
        public string|null $user_identity_id,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class UnmanagedAccessGrantErrors
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessGrantErrors|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
            missing_device_ids: $json->missing_device_ids ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
        public array|null $missing_device_ids,
    ) {}
}

class UnmanagedAccessGrantFailedDevices
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessGrantFailedDevices|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

class UnmanagedAccessGrantFrom
{
    public static function from_json(mixed $json): UnmanagedAccessGrantFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_ids: $json->device_ids ?? null,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public array|null $device_ids,
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}

class UnmanagedAccessGrantPendingMutations
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessGrantPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_method_ids: $json->access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? UnmanagedAccessGrantFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to)
                ? UnmanagedAccessGrantTo::from_json($json->to)
                : null,
        );
    }

    public function __construct(
        public array|null $access_method_ids,
        public string|null $created_at,
        public UnmanagedAccessGrantFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public UnmanagedAccessGrantTo|null $to,
    ) {}
}

class UnmanagedAccessGrantRequestedAccessMethods
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessGrantRequestedAccessMethods|null {
        if (!$json) {
            return null;
        }
        return new self(
            code: $json->code ?? null,
            created_access_method_ids: $json->created_access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            instant_key_max_use_count: $json->instant_key_max_use_count ?? null,
            mode: $json->mode ?? null,
        );
    }

    public function __construct(
        public string|null $code,
        public array|null $created_access_method_ids,
        public string|null $created_at,
        public string|null $display_name,
        public int|null $instant_key_max_use_count,
        public string|null $mode,
    ) {}
}

class UnmanagedAccessGrantTo
{
    public static function from_json(mixed $json): UnmanagedAccessGrantTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            common_code_key: $json->common_code_key ?? null,
            device_ids: $json->device_ids ?? null,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $common_code_key,
        public array|null $device_ids,
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}

class UnmanagedAccessGrantWarnings
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessGrantWarnings|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_method_ids: $json->access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            device_id: $json->device_id ?? null,
            failed_devices: array_map(
                fn($f) => UnmanagedAccessGrantFailedDevices::from_json($f),
                $json->failed_devices ?? [],
            ),
            message: $json->message ?? null,
            new_code: $json->new_code ?? null,
            original_code: $json->original_code ?? null,
            reason: $json->reason ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public array|null $access_method_ids,
        public string|null $created_at,
        public string|null $device_id,
        public array $failed_devices,
        public string|null $message,
        public string|null $new_code,
        public string|null $original_code,
        public string|null $reason,
        public string|null $warning_code,
    ) {}
}
