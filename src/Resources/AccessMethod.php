<?php

namespace Seam\Resources;

class AccessMethod
{
    public static function from_json(mixed $json): AccessMethod|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method_id: $json->access_method_id ?? null,
            client_session_token: $json->client_session_token ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at ?? null,
            customization_profile_id: $json->customization_profile_id ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => AccessMethodErrors::from_json($e),
                $json->errors ?? [],
            ),
            instant_key_url: $json->instant_key_url ?? null,
            is_assignment_required: $json->is_assignment_required ?? null,
            is_encoding_required: $json->is_encoding_required ?? null,
            is_issued: $json->is_issued ?? null,
            is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
            is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
            issued_at: $json->issued_at ?? null,
            mode: $json->mode ?? null,
            pending_mutations: array_map(
                fn($p) => AccessMethodPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            warnings: array_map(
                fn($w) => AccessMethodWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_method_id,
        public string|null $client_session_token,
        public string|null $code,
        public string|null $created_at,
        public string|null $customization_profile_id,
        public string|null $display_name,
        public array $errors,
        public string|null $instant_key_url,
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

class AccessMethodErrors
{
    public static function from_json(mixed $json): AccessMethodErrors|null
    {
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

class AccessMethodFrom
{
    public static function from_json(mixed $json): AccessMethodFrom|null
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

class AccessMethodPendingMutations
{
    public static function from_json(
        mixed $json,
    ): AccessMethodPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AccessMethodFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to) ? AccessMethodTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public AccessMethodFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public AccessMethodTo|null $to,
    ) {}
}

class AccessMethodTo
{
    public static function from_json(mixed $json): AccessMethodTo|null
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

class AccessMethodWarnings
{
    public static function from_json(mixed $json): AccessMethodWarnings|null
    {
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
