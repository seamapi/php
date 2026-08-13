<?php

namespace Seam\Resources {
    /**
     * Means by which an [access control system user](https://docs.seam.co/low-level-apis/access-systems/user-management) gains access at an [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details). The `acs_credential` object represents a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) that provides an ACS user access within an [access control system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * An access control system generally uses digital means of access to authorize a user trying to get through a specific entrance. Examples of credentials include plastic key cards, mobile keys, biometric identifiers, and PIN codes. The electronic nature of these credentials, as well as the fact that access is centralized, enables both the rapid provisioning and rescinding of access and the ability to compile access audit logs.
     *
     * For each `acs_credential`, you define the access method. You can also specify additional properties, such as a PIN code, depending on the credential type.
     *
     * For granting a person access to a space, [Access Grants](https://docs.seam.co/use-cases/granting-access) are the default and recommended approach. Use the lower-level ACS credential API directly only when you specifically need to manage individual credentials.
     */
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
                akiles_metadata: isset($json->akiles_metadata)
                    ? AcsCredential\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? AcsCredential\AssaAbloyVostioMetadata::from_json(
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
                    fn($e) => AcsCredential\Errors::from_json($e),
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
                    ? AcsCredential\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => AcsCredential\Warnings::from_json($w),
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
            public AcsCredential\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public AcsCredential\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
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
            public AcsCredential\VisionlineMetadata|null $visionline_metadata = null,
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
}

namespace Seam\Resources\AcsCredential {
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
            public string|null $new_code,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}
