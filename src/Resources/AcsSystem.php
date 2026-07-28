<?php

namespace Seam\Resources;

/**
 * Represents an [access control system](https://docs.seam.co/low-level-apis/access-systems).
 *
 * Within an `acs_system`, create [`acs_user`s](https://docs.seam.co/api/acs/users/object) and [`acs_credential`s](https://docs.seam.co/api/acs/credentials/object) to grant access to the `acs_user`s.
 *
 * For details about the resources associated with an access control system, see the [access control systems namespace](https://docs.seam.co/api/acs).
 */
class AcsSystem
{
    public static function from_json(mixed $json): AcsSystem|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_count: $json->acs_access_group_count ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_count: $json->acs_user_count ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            connected_account_ids: $json->connected_account_ids ?? null,
            created_at: $json->created_at ?? null,
            default_credential_manager_acs_system_id: $json->default_credential_manager_acs_system_id ??
                null,
            errors: array_map(
                fn($e) => AcsSystemErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            image_alt_text: $json->image_alt_text ?? null,
            image_url: $json->image_url ?? null,
            is_credential_manager: $json->is_credential_manager ?? null,
            location: isset($json->location)
                ? AcsSystemLocation::from_json($json->location)
                : null,
            name: $json->name ?? null,
            system_type: $json->system_type ?? null,
            system_type_display_name: $json->system_type_display_name ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsSystemVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => AcsSystemWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        /**
         * Number of access groups in the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public float|null $acs_access_group_count,
        /**
         * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $acs_system_id,
        /**
         * Number of users in the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public float|null $acs_user_count,
        /**
         * ID of the connected account associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $connected_account_id,
        /**
         * IDs of the [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         *
         * @deprecated Use `connected_account_id`.
         */
        public array|null $connected_account_ids,
        /**
         * Date and time at which the [access control system](https://docs.seam.co/low-level-apis/access-systems) was created.
         */
        public string|null $created_at,
        /**
         * ID of the default credential manager `acs_system` for this [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $default_credential_manager_acs_system_id,
        /**
         * Errors associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public array $errors,
        /**
         * Brand-specific terminology for the [access control system](https://docs.seam.co/low-level-apis/access-systems) type.
         */
        public string|null $external_type,
        /**
         * Display name that corresponds to the brand-specific terminology for the [access control system](https://docs.seam.co/low-level-apis/access-systems) type.
         */
        public string|null $external_type_display_name,
        /**
         * Alternative text for the [access control system](https://docs.seam.co/low-level-apis/access-systems) image.
         */
        public string|null $image_alt_text,
        /**
         * URL for the image that represents the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $image_url,
        /**
         * Indicates whether the `acs_system` is a credential manager.
         */
        public bool|null $is_credential_manager,
        /**
         * Location information for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public AcsSystemLocation|null $location,
        /**
         * Name of the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $name,
        /**
         * @deprecated Use `external_type`.
         */
        public string|null $system_type,
        /**
         * @deprecated Use `external_type_display_name`.
         */
        public string|null $system_type_display_name,
        /**
         * Visionline-specific metadata for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public AcsSystemVisionlineMetadata|null $visionline_metadata,
        /**
         * Warnings associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public array $warnings,
        /**
         * ID of the workspace that contains the [access control system](https://docs.seam.co/low-level-apis/access-systems).
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * Errors associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
 */
class AcsSystemErrors
{
    public static function from_json(mixed $json): AcsSystemErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            is_bridge_error: $json->is_bridge_error ?? null,
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
         * Indicates whether the error is related to the [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
         */
        public bool|null $is_bridge_error,
        /**
         * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
         */
        public string|null $message,
    ) {}
}

/**
 * Location information for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
 */
class AcsSystemLocation
{
    public static function from_json(mixed $json): AcsSystemLocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(time_zone: $json->time_zone ?? null);
    }

    public function __construct(
        /**
         * Time zone in which the [access control system](https://docs.seam.co/low-level-apis/access-systems) is located.
         */
        public string|null $time_zone,
    ) {}
}

/**
 * Visionline-specific metadata for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
 */
class AcsSystemVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsSystemVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            lan_address: $json->lan_address ?? null,
            mobile_access_uuid: $json->mobile_access_uuid ?? null,
            system_id: $json->system_id ?? null,
        );
    }

    public function __construct(
        /**
         * IP address or hostname of the main Visionline server relative to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge) on the local network.
         */
        public string|null $lan_address,
        /**
         * Keyset loaded into a reader. Mobile keys and reader administration tools securely authenticate only with readers programmed with a matching keyset.
         */
        public string|null $mobile_access_uuid,
        /**
         * Unique ID assigned by the ASSA ABLOY licensing team that identifies each hotel in your credential manager.
         */
        public string|null $system_id,
    ) {}
}

/**
 * Warnings associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
 */
class AcsSystemWarnings
{
    public static function from_json(mixed $json): AcsSystemWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            misconfigured_acs_entrance_ids: $json->misconfigured_acs_entrance_ids ??
                null,
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
         * @deprecated this field is deprecated.
         */
        public array|null $misconfigured_acs_entrance_ids,
        /**
         * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
         */
        public string|null $warning_code,
    ) {}
}
