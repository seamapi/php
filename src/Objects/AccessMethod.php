<?php

namespace Seam\Objects;

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
