<?php

namespace Seam\Resources;

class UnmanagedAccessMethod
{
    public static function from_json(mixed $json): UnmanagedAccessMethod|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method_id: $json->access_method_id ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => UnmanagedAccessMethodErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_assignment_required: $json->is_assignment_required ?? null,
            is_encoding_required: $json->is_encoding_required ?? null,
            is_issued: $json->is_issued ?? null,
            is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
            is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
            issued_at: $json->issued_at ?? null,
            mode: $json->mode ?? null,
            pending_mutations: array_map(
                fn($p) => UnmanagedAccessMethodPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            warnings: array_map(
                fn($w) => UnmanagedAccessMethodWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_method_id,
        public string|null $code,
        public string|null $created_at,
        public string|null $display_name,
        public array $errors,
        public bool|null $is_assignment_required,
        public bool|null $is_encoding_required,
        public bool|null $is_issued,
        public bool|null $is_ready_for_assignment,
        public bool|null $is_ready_for_encoding,
        public string|null $issued_at,
        public string|null $mode,
        public array $pending_mutations,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class UnmanagedAccessMethodErrors
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessMethodErrors|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

class UnmanagedAccessMethodFrom
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessMethodFrom|null {
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

class UnmanagedAccessMethodPendingMutations
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessMethodPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? UnmanagedAccessMethodFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to)
                ? UnmanagedAccessMethodTo::from_json($json->to)
                : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public UnmanagedAccessMethodFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public UnmanagedAccessMethodTo|null $to,
    ) {}
}

class UnmanagedAccessMethodTo
{
    public static function from_json(mixed $json): UnmanagedAccessMethodTo|null
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

class UnmanagedAccessMethodWarnings
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessMethodWarnings|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            original_access_method_id: $json->original_access_method_id ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public string|null $original_access_method_id,
        public string|null $warning_code,
    ) {}
}
