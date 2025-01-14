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
            acs_system_id: $json->acs_system_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsCredentialErrors::from_json($e),
                $json->errors ?? []
            ),
            is_managed: $json->is_managed,
            warnings: array_map(
                fn($w) => AcsCredentialWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? AcsCredentialAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata
                )
                : null,
            ends_at: $json->ends_at ?? null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_issued: $json->is_issued ?? null,
            is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                null,
            is_one_time_use: $json->is_one_time_use ?? null,
            parent_acs_credential_id: $json->parent_acs_credential_id ?? null,
            starts_at: $json->starts_at ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsCredentialVisionlineMetadata::from_json(
                    $json->visionline_metadata
                )
                : null,
            card_number: $json->card_number ?? null,
            code: $json->code ?? null,
            is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                null,
            issued_at: $json->issued_at ?? null,
            latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                null
        );
    }

    public function __construct(
        public string $access_method,
        public string $acs_credential_id,
        public string $acs_system_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public bool $is_managed,
        public array $warnings,
        public string $workspace_id,
        public string|null $acs_credential_pool_id,
        public string|null $acs_user_id,
        public AcsCredentialAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public string|null $ends_at,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public bool|null $is_issued,
        public bool|null $is_multi_phone_sync_credential,
        public bool|null $is_one_time_use,
        public string|null $parent_acs_credential_id,
        public string|null $starts_at,
        public AcsCredentialVisionlineMetadata|null $visionline_metadata,
        public string|null $card_number,
        public string|null $code,
        public bool|null $is_latest_desired_state_synced_with_provider,
        public string|null $issued_at,
        public string|null $latest_desired_state_synced_with_provider_at
    ) {
    }
}
