<?php

namespace Seam\Objects;

class PhoneSessionAcsCredentials
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionAcsCredentials|null {
        if (!$json) {
            return null;
        }
        return new self(
            access_method: $json->access_method ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_entrances: array_map(
                fn($a) => PhoneSessionAcsEntrances::from_json($a),
                $json->acs_entrances ?? [],
            ),
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? PhoneSessionAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            card_number: $json->card_number ?? null,
            code: $json->code ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => PhoneSessionErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_issued: $json->is_issued ?? null,
            is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                null,
            is_managed: $json->is_managed ?? null,
            is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                null,
            is_one_time_use: $json->is_one_time_use ?? null,
            issued_at: $json->issued_at ?? null,
            latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                null,
            parent_acs_credential_id: $json->parent_acs_credential_id ?? null,
            starts_at: $json->starts_at ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? PhoneSessionVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => PhoneSessionWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_method,
        public string|null $acs_credential_id,
        public string|null $acs_credential_pool_id,
        public array $acs_entrances,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public PhoneSessionAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public string|null $card_number,
        public string|null $code,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $ends_at,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public bool|null $is_issued,
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool|null $is_managed,
        public bool|null $is_multi_phone_sync_credential,
        public bool|null $is_one_time_use,
        public string|null $issued_at,
        public string|null $latest_desired_state_synced_with_provider_at,
        public string|null $parent_acs_credential_id,
        public string|null $starts_at,
        public string|null $user_identity_id,
        public PhoneSessionVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
