<?php

namespace Seam\Resources {
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
                    ? ActionAttempt\Error::from_json($json->error)
                    : null,
                result: isset($json->result)
                    ? ActionAttempt\Result::from_json($json->result)
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            public string|null $action_attempt_id,
            public string|null $action_type,
            /**
             * Error associated with the action.
             */
            public ActionAttempt\Error|null $error,
            public ActionAttempt\Result|null $result,
            public string|null $status,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt {
    /**
     * Error associated with the action.
     */
    class Error
    {
        public static function from_json(mixed $json): Error|null
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
            public string|null $type,
        ) {}
    }

    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code: $json->access_code ?? null,
                access_method: $json->access_method ?? null,
                access_method_id: $json->access_method_id ?? null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_credential_on_encoder: isset(
                    $json->acs_credential_on_encoder,
                )
                    ? Result\AcsCredentialOnEncoder::from_json(
                        $json->acs_credential_on_encoder,
                    )
                    : null,
                acs_credential_on_seam: isset($json->acs_credential_on_seam)
                    ? Result\AcsCredentialOnSeam::from_json(
                        $json->acs_credential_on_seam,
                    )
                    : null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? Result\AkilesMetadata::from_json($json->akiles_metadata)
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? Result\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                client_session_token: $json->client_session_token ?? null,
                code: $json->code ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                display_name: $json->display_name ?? null,
                ends_at: $json->ends_at ?? null,
                errors: array_map(
                    fn($e) => Result\Errors::from_json($e),
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
                noise_threshold: $json->noise_threshold ?? null,
                parent_acs_credential_id: $json->parent_acs_credential_id ??
                    null,
                pending_mutations: array_map(
                    fn($p) => Result\PendingMutations::from_json($p),
                    $json->pending_mutations ?? [],
                ),
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? Result\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => Result\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                was_confirmed_by_device: $json->was_confirmed_by_device ?? null,
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $access_code,
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
            public Result\AcsCredentialOnEncoder|null $acs_credential_on_encoder,
            /**
             * Corresponding credential data as stored on Seam and the access system.
             */
            public Result\AcsCredentialOnSeam|null $acs_credential_on_seam,
            /**
             * ID of the credential pool to which the credential belongs.
             */
            public string|null $acs_credential_pool_id = null,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $acs_user_id = null,
            /**
             * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public Result\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public Result\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number = null,
            /**
             * Token of the client session associated with the access method.
             */
            public string|null $client_session_token = null,
            public string|null $code = null,
            /**
             * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $connected_account_id,
            public string|null $created_at,
            /**
             * ID of the customization profile associated with the access method.
             */
            public string|null $customization_profile_id = null,
            public string|null $display_name,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
             */
            public string|null $ends_at = null,
            public array $errors,
            /**
             * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
             */
            public string|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * URL of the Instant Key for mobile key access methods.
             */
            public string|null $instant_key_url = null,
            /**
             * Indicates whether an existing card credential must be assigned to this access method before it can be issued. Only applies to card-mode access methods on systems that support credential assignment.
             */
            public bool|null $is_assignment_required = null,
            /**
             * Indicates whether encoding with an card encoder is required to issue or reissue the plastic card associated with the access method.
             */
            public bool|null $is_encoding_required = null,
            public bool|null $is_issued = null,
            /**
             * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
             */
            public bool|null $is_latest_desired_state_synced_with_provider = null,
            /**
             * Indicates whether Seam manages the credential.
             */
            public bool|null $is_managed,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
             */
            public bool|null $is_multi_phone_sync_credential = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use = null,
            /**
             * Indicates whether the access method is ready for card assignment. This is true when the access method is in card mode, has not yet been issued, and the system supports credential assignment.
             */
            public bool|null $is_ready_for_assignment = null,
            /**
             * Indicates whether the access method is ready to be encoded. This is true when the credential has been created and the card has not yet been issued.
             */
            public bool|null $is_ready_for_encoding = null,
            public string|null $issued_at = null,
            /**
             * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
             */
            public string|null $latest_desired_state_synced_with_provider_at = null,
            /**
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public string|null $mode,
            /**
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $noise_threshold,
            /**
             * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $parent_acs_credential_id = null,
            /**
             * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
             */
            public array $pending_mutations,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at = null,
            /**
             * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $user_identity_id = null,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public Result\VisionlineMetadata|null $visionline_metadata = null,
            public array $warnings,
            public bool|null $was_confirmed_by_device = null,
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\Result {
    /**
     * Snapshot of credential data read from the physical encoder.
     */
    class AcsCredentialOnEncoder
    {
        public static function from_json(
            mixed $json,
        ): AcsCredentialOnEncoder|null {
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
                    ? AcsCredentialOnEncoder\VisionlineMetadata::from_json(
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
            public AcsCredentialOnEncoder\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }

    /**
     * Corresponding credential data as stored on Seam and the access system.
     */
    class AcsCredentialOnSeam
    {
        public static function from_json(mixed $json): AcsCredentialOnSeam|null
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
                akiles_metadata: isset($json->akiles_metadata)
                    ? AcsCredentialOnSeam\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? AcsCredentialOnSeam\AssaAbloyVostioMetadata::from_json(
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
                    fn($e) => AcsCredentialOnSeam\Errors::from_json($e),
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
                parent_acs_credential_id: $json->parent_acs_credential_id ??
                    null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? AcsCredentialOnSeam\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => AcsCredentialOnSeam\Warnings::from_json($w),
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
            public string|null $acs_credential_pool_id = null,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $acs_user_id = null,
            /**
             * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public AcsCredentialOnSeam\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public AcsCredentialOnSeam\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number = null,
            /**
             * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $code = null,
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
            public string|null $ends_at = null,
            /**
             * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public array $errors,
            /**
             * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
             */
            public string|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
             */
            public bool|null $is_issued = null,
            /**
             * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
             */
            public bool|null $is_latest_desired_state_synced_with_provider = null,
            public bool|null $is_managed,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
             */
            public bool|null $is_multi_phone_sync_credential = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
             */
            public string|null $issued_at = null,
            /**
             * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
             */
            public string|null $latest_desired_state_synced_with_provider_at = null,
            /**
             * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $parent_acs_credential_id = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at = null,
            /**
             * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $user_identity_id = null,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public AcsCredentialOnSeam\VisionlineMetadata|null $visionline_metadata = null,
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
     * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(member_pin_id: $json->member_pin_id ?? null);
        }

        public function __construct(
            /**
             * ID of the Akiles member PIN.
             */
            public string|null $member_pin_id = null,
        ) {}
    }

    /**
     * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
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
            public bool|null $auto_join = null,
            /**
             * Names of the doors to which to grant access in the Vostio access system.
             */
            public array|null $door_names = null,
            /**
             * Endpoint ID in the Vostio access system.
             */
            public string|null $endpoint_id = null,
            /**
             * Key ID in the Vostio access system.
             */
            public string|null $key_id = null,
            /**
             * Key issuing request ID in the Vostio access system.
             */
            public string|null $key_issuing_request_id = null,
            /**
             * IDs of the guest entrances to override in the Vostio access system.
             */
            public array|null $override_guest_acs_entrance_ids = null,
        ) {}
    }

    class Errors
    {
        public static function from_json(mixed $json): Errors|null
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
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? PendingMutations\From::from_json($json->from)
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? PendingMutations\To::from_json($json->to)
                    : null,
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
            public PendingMutations\From|null $from,
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
            public PendingMutations\To|null $to,
        ) {}
    }

    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
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
                joiner_acs_credential_ids: $json->joiner_acs_credential_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Card function type in the Visionline access system.
             */
            public string|null $card_function_type = null,
            /**
             * ID of the card in the Visionline access system.
             */
            public string|null $card_id = null,
            /**
             * Common entrance IDs in the Visionline access system.
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * ID of the credential in the Visionline access system.
             */
            public string|null $credential_id = null,
            /**
             * Guest entrance IDs in the Visionline access system.
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Indicates whether the credential is valid.
             */
            public bool|null $is_valid = null,
            /**
             * IDs of the credentials to which you want to join.
             */
            public array|null $joiner_acs_credential_ids = null,
        ) {}
    }

    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                new_code: $json->new_code ?? null,
                original_access_method_id: $json->original_access_method_id ??
                    null,
                original_code: $json->original_code ?? null,
                warning_code: $json->warning_code ?? null,
                warning_message: $json->warning_message ?? null,
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
             * The PIN code that was assigned instead.
             */
            public string|null $new_code = null,
            /**
             * ID of the original access method from which this backup access method was split, if applicable.
             */
            public string|null $original_access_method_id = null,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code = null,
            public string|null $warning_code,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $warning_message,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\Result\AcsCredentialOnEncoder {
    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
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
            public bool|null $cancelled = null,
            /**
             * Format of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_format = null,
            /**
             * Holder of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_holder = null,
            /**
             * Card ID for the Visionline card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_id = null,
            /**
             * IDs of the common [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is discarded.
             */
            public bool|null $discarded = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is expired.
             */
            public bool|null $expired = null,
            /**
             * IDs of the guest [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Number of issued cards associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public float|null $number_of_issued_cards = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overridden.
             */
            public bool|null $overridden = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overwritten.
             */
            public bool|null $overwritten = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is pending auto-update.
             */
            public bool|null $pending_auto_update = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\Result\AcsCredentialOnSeam {
    /**
     * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(member_pin_id: $json->member_pin_id ?? null);
        }

        public function __construct(
            /**
             * ID of the Akiles member PIN.
             */
            public string|null $member_pin_id = null,
        ) {}
    }

    /**
     * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
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
            public bool|null $auto_join = null,
            /**
             * Names of the doors to which to grant access in the Vostio access system.
             */
            public array|null $door_names = null,
            /**
             * Endpoint ID in the Vostio access system.
             */
            public string|null $endpoint_id = null,
            /**
             * Key ID in the Vostio access system.
             */
            public string|null $key_id = null,
            /**
             * Key issuing request ID in the Vostio access system.
             */
            public string|null $key_issuing_request_id = null,
            /**
             * IDs of the guest entrances to override in the Vostio access system.
             */
            public array|null $override_guest_acs_entrance_ids = null,
        ) {}
    }

    /**
     * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
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
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
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
                joiner_acs_credential_ids: $json->joiner_acs_credential_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Card function type in the Visionline access system.
             */
            public string|null $card_function_type = null,
            /**
             * ID of the card in the Visionline access system.
             */
            public string|null $card_id = null,
            /**
             * Common entrance IDs in the Visionline access system.
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * ID of the credential in the Visionline access system.
             */
            public string|null $credential_id = null,
            /**
             * Guest entrance IDs in the Visionline access system.
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Indicates whether the credential is valid.
             */
            public bool|null $is_valid = null,
            /**
             * IDs of the credentials to which you want to join.
             */
            public array|null $joiner_acs_credential_ids = null,
        ) {}
    }

    /**
     * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
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
             * The PIN code that was assigned instead.
             */
            public string|null $new_code = null,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code = null,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\Result\PendingMutations {
    /**
     * Previous access time configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
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
     * New access time configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
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
}
