<?php

namespace Seam\Objects;

class ActionAttemptResult
{
    public static function from_json(mixed $json): ActionAttemptResult|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method: $json->access_method ?? null,
            access_method_id: $json->access_method_id ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_credential_on_encoder: isset($json->acs_credential_on_encoder)
                ? ActionAttemptAcsCredentialOnEncoder::from_json(
                    $json->acs_credential_on_encoder,
                )
                : null,
            acs_credential_on_seam: isset($json->acs_credential_on_seam)
                ? ActionAttemptAcsCredentialOnSeam::from_json(
                    $json->acs_credential_on_seam,
                )
                : null,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? ActionAttemptAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            card_number: $json->card_number ?? null,
            client_session_token: $json->client_session_token ?? null,
            code: $json->code ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            customization_profile_id: $json->customization_profile_id ?? null,
            display_name: $json->display_name ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => ActionAttemptErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            instant_key_url: $json->instant_key_url ?? null,
            is_assignment_required: $json->is_assignment_required ?? null,
            is_encoding_required: $json->is_encoding_required ?? null,
            is_issued: $json->is_issued ?? null,
            is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                null,
            is_managed: $json->is_managed ?? null,
            is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                null,
            is_one_time_use: $json->is_one_time_use ?? null,
            is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
            is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
            issued_at: $json->issued_at ?? null,
            latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                null,
            mode: $json->mode ?? null,
            parent_acs_credential_id: $json->parent_acs_credential_id ?? null,
            pending_mutations: array_map(
                fn($p) => ActionAttemptPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            starts_at: $json->starts_at ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? ActionAttemptVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => ActionAttemptWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            was_confirmed_by_device: $json->was_confirmed_by_device ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_method,
        public string|null $access_method_id,
        public string|null $acs_credential_id,
        public ActionAttemptAcsCredentialOnEncoder|null $acs_credential_on_encoder,
        public ActionAttemptAcsCredentialOnSeam|null $acs_credential_on_seam,
        public string|null $acs_credential_pool_id,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public ActionAttemptAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public string|null $card_number,
        public string|null $client_session_token,
        public string|null $code,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $customization_profile_id,
        public string|null $display_name,
        public string|null $ends_at,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $instant_key_url,
        public bool|null $is_assignment_required,
        public bool|null $is_encoding_required,
        public bool|null $is_issued,
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool|null $is_managed,
        public bool|null $is_multi_phone_sync_credential,
        public bool|null $is_one_time_use,
        public bool|null $is_ready_for_assignment,
        public bool|null $is_ready_for_encoding,
        public string|null $issued_at,
        public string|null $latest_desired_state_synced_with_provider_at,
        public string|null $mode,
        public string|null $parent_acs_credential_id,
        public array $pending_mutations,
        public string|null $starts_at,
        public string|null $user_identity_id,
        public ActionAttemptVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
        public bool|null $was_confirmed_by_device,
        public string|null $workspace_id,
    ) {}
}
