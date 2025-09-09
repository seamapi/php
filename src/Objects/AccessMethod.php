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
            access_method_id: $json->access_method_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            is_issued: $json->is_issued,
            mode: $json->mode,
            warnings: array_map(
                fn($w) => AccessMethodWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id,
            client_session_token: $json->client_session_token ?? null,
            customization_profile_id: $json->customization_profile_id ?? null,
            instant_key_url: $json->instant_key_url ?? null,
            is_encoding_required: $json->is_encoding_required ?? null,
            issued_at: $json->issued_at ?? null,
            code: $json->code ?? null,
        );
    }

    public function __construct(
        public string $access_method_id,
        public string $created_at,
        public string $display_name,
        public bool $is_issued,
        public string $mode,
        public array $warnings,
        public string $workspace_id,
        public string|null $client_session_token,
        public string|null $customization_profile_id,
        public string|null $instant_key_url,
        public bool|null $is_encoding_required,
        public string|null $issued_at,
        public string|null $code,
    ) {}
}
