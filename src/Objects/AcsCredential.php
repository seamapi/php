<?php

namespace Seam\Objects;

class AcsCredential
{
    public static function from_json(mixed $json): AcsCredential|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method: $json->access_method,
            acs_credential_id: $json->acs_credential_id,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_system_id: $json->acs_system_id,
            acs_user_id: $json->acs_user_id ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at,
            display_name: $json->display_name,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => AcsCredentialErrors::from_json($e),
                $json->errors ?? []
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                null,
            is_managed: $json->is_managed,
            is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                null,
            latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                null,
            parent_acs_credential_id: $json->parent_acs_credential_id ?? null,
            starts_at: $json->starts_at ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsCredentialVisionlineMetadata::from_json(
                    $json->visionline_metadata
                )
                : null,
            warnings: array_map(
                fn($w) => AcsCredentialWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $access_method,
        public string $acs_credential_id,
        public string|null $acs_credential_pool_id,
        public string $acs_system_id,
        public string|null $acs_user_id,
        public string|null $code,
        public string $created_at,
        public string $display_name,
        public string|null $ends_at,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool $is_managed,
        public bool|null $is_multi_phone_sync_credential,
        public string|null $latest_desired_state_synced_with_provider_at,
        public string|null $parent_acs_credential_id,
        public string|null $starts_at,
        public AcsCredentialVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
        public string $workspace_id
    ) {
    }
}
