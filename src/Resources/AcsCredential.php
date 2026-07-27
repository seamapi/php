<?php

namespace Seam\Resources;

class AcsCredential
{
    public static function from_json(mixed $json): AcsCredential|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method: $json->access_method ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? AcsCredentialAssaAbloyVostioMetadata::from_json(
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
                fn($e) => AcsCredentialErrors::from_json($e),
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
                ? AcsCredentialVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => AcsCredentialWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_method,
        public string|null $acs_credential_id,
        public string|null $acs_credential_pool_id,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public AcsCredentialAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
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
        public AcsCredentialVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class AcsCredentialAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsCredentialAssaAbloyVostioMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            auto_join: $json->auto_join ?? null,
            door_names: $json->door_names ?? null,
            endpoint_id: $json->endpoint_id ?? null,
            key_id: $json->key_id ?? null,
            key_issuing_request_id: $json->key_issuing_request_id ?? null,
            override_guest_acs_entrance_ids: $json->override_guest_acs_entrance_ids ??
                null,
        );
    }

    public function __construct(
        public bool|null $auto_join,
        public array|null $door_names,
        public string|null $endpoint_id,
        public string|null $key_id,
        public string|null $key_issuing_request_id,
        public array|null $override_guest_acs_entrance_ids,
    ) {}
}

class AcsCredentialErrors
{
    public static function from_json(mixed $json): AcsCredentialErrors|null
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

class AcsCredentialVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsCredentialVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            auto_join: $json->auto_join ?? null,
            card_function_type: $json->card_function_type ?? null,
            card_id: $json->card_id ?? null,
            common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
            credential_id: $json->credential_id ?? null,
            guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
            is_valid: $json->is_valid ?? null,
            joiner_acs_credential_ids: $json->joiner_acs_credential_ids ?? null,
        );
    }

    public function __construct(
        public bool|null $auto_join,
        public string|null $card_function_type,
        public string|null $card_id,
        public array|null $common_acs_entrance_ids,
        public string|null $credential_id,
        public array|null $guest_acs_entrance_ids,
        public bool|null $is_valid,
        public array|null $joiner_acs_credential_ids,
    ) {}
}

class AcsCredentialWarnings
{
    public static function from_json(mixed $json): AcsCredentialWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
