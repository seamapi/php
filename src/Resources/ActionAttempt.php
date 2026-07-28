<?php

namespace Seam\Resources;

/**
 * Locking a door is pending.
 */
class ActionAttempt
{
    public static function from_json(mixed $json): ActionAttempt|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            action_attempt_id: $json->action_attempt_id ?? null,
            action_type: $json->action_type ?? null,
            error: isset($json->error)
                ? ActionAttemptError::from_json($json->error)
                : null,
            result: isset($json->result)
                ? ActionAttemptResult::from_json($json->result)
                : null,
            status: $json->status ?? null,
        );
    }

    public function __construct(
        /**
         * ID of the action attempt.
         */
        public string|null $action_attempt_id,
        /**
         * Action attempt to track the status of locking a door.
         */
        public string|null $action_type,
        /**
         * Error associated with the action.
         */
        public ActionAttemptError|null $error,
        /**
         * Result of the action.
         */
        public ActionAttemptResult|null $result,
        public string|null $status,
    ) {}
}

/**
 * Snapshot of credential data read from the physical encoder.
 */
class ActionAttemptAcsCredentialOnEncoder
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptAcsCredentialOnEncoder|null {
        if (!$json) {
            return null;
        }
        return new self(
            card_number: $json->card_number ?? null,
            created_at: $json->created_at ?? null,
            ends_at: $json->ends_at ?? null,
            is_issued: $json->is_issued ?? null,
            starts_at: $json->starts_at ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? ActionAttemptVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
        );
    }

    public function __construct(
        /**
         * A number or string that physically identifies the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_number,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
         */
        public string|null $created_at,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) will stop being usable.
         */
        public string|null $ends_at,
        /**
         * Indicates whether the credential has been issued (encoded onto a card).
         */
        public bool|null $is_issued,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) becomes usable.
         */
        public string|null $starts_at,
        /**
         * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public ActionAttemptVisionlineMetadata|null $visionline_metadata,
    ) {}
}

/**
 * Corresponding credential data as stored on Seam and the access system.
 */
class ActionAttemptAcsCredentialOnSeam
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptAcsCredentialOnSeam|null {
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
                ? ActionAttemptAssaAbloyVostioMetadata::from_json(
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
                fn($e) => ActionAttemptErrors::from_json($e),
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
                ? ActionAttemptVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => ActionAttemptWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        /**
         * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
         */
        public string|null $access_method,
        /**
         * ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $acs_credential_id,
        /**
         * ID of the credential pool to which the credential belongs.
         */
        public string|null $acs_credential_pool_id,
        /**
         * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $acs_system_id,
        /**
         * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $acs_user_id,
        /**
         * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public ActionAttemptAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        /**
         * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_number,
        /**
         * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $code,
        /**
         * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $connected_account_id,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
         */
        public string|null $created_at,
        /**
         * Display name that corresponds to the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
         */
        public string|null $display_name,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
         */
        public string|null $ends_at,
        /**
         * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public array $errors,
        /**
         * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
         */
        public string|null $external_type,
        /**
         * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
         */
        public string|null $external_type_display_name,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
         */
        public bool|null $is_issued,
        /**
         * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
         */
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool|null $is_managed,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
         */
        public bool|null $is_multi_phone_sync_credential,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
         */
        public bool|null $is_one_time_use,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
         */
        public string|null $issued_at,
        /**
         * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
         */
        public string|null $latest_desired_state_synced_with_provider_at,
        /**
         * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $parent_acs_credential_id,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
         */
        public string|null $starts_at,
        /**
         * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $user_identity_id,
        /**
         * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public ActionAttemptVisionlineMetadata|null $visionline_metadata,
        /**
         * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public array $warnings,
        /**
         * ID of the workspace that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
 */
class ActionAttemptAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptAssaAbloyVostioMetadata|null {
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
        /**
         * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
         */
        public bool|null $auto_join,
        /**
         * Names of the doors to which to grant access in the Vostio access system.
         */
        public array|null $door_names,
        /**
         * Endpoint ID in the Vostio access system.
         */
        public string|null $endpoint_id,
        /**
         * Key ID in the Vostio access system.
         */
        public string|null $key_id,
        /**
         * Key issuing request ID in the Vostio access system.
         */
        public string|null $key_issuing_request_id,
        /**
         * IDs of the guest entrances to override in the Vostio access system.
         */
        public array|null $override_guest_acs_entrance_ids,
    ) {}
}

/**
 * Error associated with the action.
 */
class ActionAttemptError
{
    public static function from_json(mixed $json): ActionAttemptError|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message ?? null,
            type: $json->type ?? null,
        );
    }

    public function __construct(
        /**
         * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
        /**
         * Type of the error.
         */
        public string|null $type,
    ) {}
}

/**
 * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
 */
class ActionAttemptErrors
{
    public static function from_json(mixed $json): ActionAttemptErrors|null
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
        /**
         * Date and time at which Seam created the error.
         */
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

/**
 * Previous access time configuration.
 */
class ActionAttemptFrom
{
    public static function from_json(mixed $json): ActionAttemptFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * Previous end time for access.
         */
        public string|null $ends_at,
        /**
         * Previous start time for access.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
 */
class ActionAttemptPendingMutations
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? ActionAttemptFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to) ? ActionAttemptTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        /**
         * Date and time at which the mutation was created.
         */
        public string|null $created_at,
        /**
         * Previous access time configuration.
         */
        public ActionAttemptFrom|null $from,
        /**
         * Detailed description of the mutation.
         */
        public string|null $message,
        /**
         * Mutation code to indicate that Seam is in the process of updating the access times for this access method.
         */
        public string|null $mutation_code,
        /**
         * New access time configuration.
         */
        public ActionAttemptTo|null $to,
    ) {}
}

/**
 * Result of the action.
 */
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
        /**
         * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
         */
        public string|null $access_method,
        /**
         * ID of the access method.
         */
        public string|null $access_method_id,
        /**
         * ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $acs_credential_id,
        /**
         * Snapshot of credential data read from the physical encoder.
         */
        public ActionAttemptAcsCredentialOnEncoder|null $acs_credential_on_encoder,
        /**
         * Corresponding credential data as stored on Seam and the access system.
         */
        public ActionAttemptAcsCredentialOnSeam|null $acs_credential_on_seam,
        /**
         * ID of the credential pool to which the credential belongs.
         */
        public string|null $acs_credential_pool_id,
        /**
         * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $acs_system_id,
        /**
         * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $acs_user_id,
        /**
         * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public ActionAttemptAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        /**
         * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_number,
        /**
         * Token of the client session associated with the access method.
         */
        public string|null $client_session_token,
        /**
         * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $code,
        /**
         * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $connected_account_id,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
         */
        public string|null $created_at,
        /**
         * ID of the customization profile associated with the access method.
         */
        public string|null $customization_profile_id,
        /**
         * Display name that corresponds to the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
         */
        public string|null $display_name,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
         */
        public string|null $ends_at,
        /**
         * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public array $errors,
        /**
         * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
         */
        public string|null $external_type,
        /**
         * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
         */
        public string|null $external_type_display_name,
        /**
         * URL of the Instant Key for mobile key access methods.
         */
        public string|null $instant_key_url,
        /**
         * Indicates whether an existing card credential must be assigned to this access method before it can be issued. Only applies to card-mode access methods on systems that support credential assignment.
         */
        public bool|null $is_assignment_required,
        /**
         * Indicates whether encoding with an card encoder is required to issue or reissue the plastic card associated with the access method.
         */
        public bool|null $is_encoding_required,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
         */
        public bool|null $is_issued,
        /**
         * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
         */
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool|null $is_managed,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
         */
        public bool|null $is_multi_phone_sync_credential,
        /**
         * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
         */
        public bool|null $is_one_time_use,
        /**
         * Indicates whether the access method is ready for card assignment. This is true when the access method is in card mode, has not yet been issued, and the system supports credential assignment.
         */
        public bool|null $is_ready_for_assignment,
        /**
         * Indicates whether the access method is ready to be encoded. This is true when the credential has been created and the card has not yet been issued.
         */
        public bool|null $is_ready_for_encoding,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
         */
        public string|null $issued_at,
        /**
         * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
         */
        public string|null $latest_desired_state_synced_with_provider_at,
        /**
         * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
         */
        public string|null $mode,
        /**
         * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $parent_acs_credential_id,
        /**
         * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
         */
        public array $pending_mutations,
        /**
         * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
         */
        public string|null $starts_at,
        /**
         * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
         */
        public string|null $user_identity_id,
        /**
         * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public ActionAttemptVisionlineMetadata|null $visionline_metadata,
        /**
         * Warnings related to scanning the credential, such as mismatches between the credential data currently encoded on the card and the corresponding data stored on Seam and the access system.
         */
        public array $warnings,
        /**
         * Indicates whether the device confirmed that the lock action occurred.
         */
        public bool|null $was_confirmed_by_device,
        /**
         * ID of the workspace that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * New access time configuration.
 */
class ActionAttemptTo
{
    public static function from_json(mixed $json): ActionAttemptTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        /**
         * New end time for access.
         */
        public string|null $ends_at,
        /**
         * New start time for access.
         */
        public string|null $starts_at,
    ) {}
}

/**
 * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
 */
class ActionAttemptVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            cancelled: $json->cancelled ?? null,
            card_format: $json->card_format ?? null,
            card_holder: $json->card_holder ?? null,
            card_id: $json->card_id ?? null,
            common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
            discarded: $json->discarded ?? null,
            expired: $json->expired ?? null,
            guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
            number_of_issued_cards: $json->number_of_issued_cards ?? null,
            overridden: $json->overridden ?? null,
            overwritten: $json->overwritten ?? null,
            pending_auto_update: $json->pending_auto_update ?? null,
        );
    }

    public function __construct(
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is cancelled.
         */
        public bool|null $cancelled,
        /**
         * Format of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_format,
        /**
         * Holder of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_holder,
        /**
         * Card ID for the Visionline card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public string|null $card_id,
        /**
         * IDs of the common [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public array|null $common_acs_entrance_ids,
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is discarded.
         */
        public bool|null $discarded,
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is expired.
         */
        public bool|null $expired,
        /**
         * IDs of the guest [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public array|null $guest_acs_entrance_ids,
        /**
         * Number of issued cards associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
         */
        public float|null $number_of_issued_cards,
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overridden.
         */
        public bool|null $overridden,
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overwritten.
         */
        public bool|null $overwritten,
        /**
         * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is pending auto-update.
         */
        public bool|null $pending_auto_update,
    ) {}
}

/**
 * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
 */
class ActionAttemptWarnings
{
    public static function from_json(mixed $json): ActionAttemptWarnings|null
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
        /**
         * Date and time at which Seam created the warning.
         */
        public string|null $created_at,
        /**
         * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
        /**
         * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
         */
        public string|null $warning_code,
    ) {}
}
