<?php

namespace Seam\Resources {
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
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_ids: $json->connected_account_ids ?? null,
                created_at: $json->created_at ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AcsSystem\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                image_alt_text: $json->image_alt_text ?? null,
                image_url: $json->image_url ?? null,
                is_credential_manager: $json->is_credential_manager ?? null,
                location: isset($json->location)
                    ? \Seam\Resources\AcsSystem\Location::from_json(
                        $json->location,
                    )
                    : null,
                name: $json->name ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\AcsSystem\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_access_group_count: $json->acs_access_group_count ?? null,
                acs_user_count: $json->acs_user_count ?? null,
                default_credential_manager_acs_system_id: $json->default_credential_manager_acs_system_id ??
                    null,
                external_type: $json->external_type ?? null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                system_type: $json->system_type ?? null,
                system_type_display_name: $json->system_type_display_name ??
                    null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? \Seam\Resources\AcsSystem\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public string|null $acs_system_id,
            /**
             * ID of the connected account associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public string|null $connected_account_id,
            /**
             * IDs of the [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             *
             * @var list<string>|null
             * @deprecated Use `connected_account_id`.
             */
            public array|null $connected_account_ids,
            /**
             * Date and time at which the [access control system](https://docs.seam.co/low-level-apis/access-systems) was created.
             */
            public string|null $created_at,
            /**
             * Errors associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             *
             * @var list<\Seam\Resources\AcsSystem\Errors>
             */
            public array $errors,
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
            public \Seam\Resources\AcsSystem\Location|null $location,
            /**
             * Name of the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public string|null $name,
            /**
             * Warnings associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             *
             * @var list<\Seam\Resources\AcsSystem\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public string|null $workspace_id,
            /**
             * Number of access groups in the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public float|null $acs_access_group_count = null,
            /**
             * Number of users in the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public float|null $acs_user_count = null,
            /**
             * ID of the default credential manager `acs_system` for this [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public string|null $default_credential_manager_acs_system_id = null,
            /**
             * Brand-specific terminology for the [access control system](https://docs.seam.co/low-level-apis/access-systems) type.
             *
             * @var value-of<\Seam\Resources\AcsSystem\ExternalType>|string|null
             */
            public string|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [access control system](https://docs.seam.co/low-level-apis/access-systems) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * @var value-of<\Seam\Resources\AcsSystem\SystemType>|string|null
             * @deprecated Use `external_type`.
             */
            public string|null $system_type = null,
            /**
             * @deprecated Use `external_type_display_name`.
             */
            public string|null $system_type_display_name = null,
            /**
             * Visionline-specific metadata for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
             */
            public \Seam\Resources\AcsSystem\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }
}

namespace Seam\Resources\AcsSystem {
    /**
     * Errors associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems). Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\AcsSystem\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsSystem\Errors\ErrorCode::SEAM_BRIDGE_DISCONNECTED
                    => \Seam\Resources\AcsSystem\Errors\SeamBridgeDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\AcsSystem\Errors\BridgeDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::VISIONLINE_INSTANCE_UNREACHABLE
                    => \Seam\Resources\AcsSystem\Errors\VisionlineInstanceUnreachable::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\AcsSystem\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::INSUFFICIENT_PERMISSIONS
                    => \Seam\Resources\AcsSystem\Errors\InsufficientPermissions::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::ACS_SYSTEM_DISCONNECTED
                    => \Seam\Resources\AcsSystem\Errors\AcsSystemDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\AcsSystem\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::SALTO_KS_CERTIFICATION_EXPIRED
                    => \Seam\Resources\AcsSystem\Errors\SaltoKsCertificationExpired::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Errors\ErrorCode::PROVIDER_SERVICE_UNAVAILABLE
                    => \Seam\Resources\AcsSystem\Errors\ProviderServiceUnavailable::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    error_code: $json->error_code ?? null,
                    message: $json->message ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Location information for the [access control system](https://docs.seam.co/low-level-apis/access-systems).
     */
    class Location
    {
        public static function from_json(mixed $json): Location|null
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
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
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
            public string|null $lan_address = null,
            /**
             * Keyset loaded into a reader. Mobile keys and reader administration tools securely authenticate only with readers programmed with a matching keyset.
             */
            public string|null $mobile_access_uuid = null,
            /**
             * Unique ID assigned by the ASSA ABLOY licensing team that identifies each hotel in your credential manager.
             */
            public string|null $system_id = null,
        ) {}
    }

    /**
     * Warnings associated with the [access control system](https://docs.seam.co/low-level-apis/access-systems). Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\AcsSystem\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\AcsSystem\Warnings\WarningCode::SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED
                    => \Seam\Resources\AcsSystem\Warnings\SaltoKsSubscriptionLimitAlmostReached::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Warnings\WarningCode::TIME_ZONE_DOES_NOT_MATCH_LOCATION
                    => \Seam\Resources\AcsSystem\Warnings\TimeZoneDoesNotMatchLocation::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Warnings\WarningCode::SETUP_REQUIRED
                    => \Seam\Resources\AcsSystem\Warnings\SetupRequired::from_json(
                    $json,
                ),
                \Seam\Resources\AcsSystem\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_ACS_SYSTEM
                    => \Seam\Resources\AcsSystem\Warnings\UnknownIssueWithAcsSystem::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    warning_code: $json->warning_code ?? null,
                ),
            };
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
             *
             * @var value-of<\Seam\Resources\AcsSystem\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }

    enum ExternalType: string
    {
        case PTI_SITE = "pti_site";
        case AVIGILON_ALTA_ORG = "avigilon_alta_org";
        case SALTO_KS_SITE = "salto_ks_site";
        case SALTO_SPACE_SYSTEM = "salto_space_system";
        case BRIVO_ACCOUNT = "brivo_account";
        case HID_CREDENTIAL_MANAGER_ORGANIZATION = "hid_credential_manager_organization";
        case VISIONLINE_SYSTEM = "visionline_system";
        case ASSA_ABLOY_CREDENTIAL_SERVICE = "assa_abloy_credential_service";
        case LATCH_BUILDING = "latch_building";
        case DORMAKABA_COMMUNITY_SITE = "dormakaba_community_site";
        case DORMAKABA_AMBIANCE_SITE = "dormakaba_ambiance_site";
        case LEGIC_CONNECT_CREDENTIAL_SERVICE = "legic_connect_credential_service";
        case ASSA_ABLOY_VOSTIO = "assa_abloy_vostio";
        case ASSA_ABLOY_VOSTIO_CREDENTIAL_SERVICE = "assa_abloy_vostio_credential_service";
        case HOTEK_SITE = "hotek_site";
        case KISI_ORGANIZATION = "kisi_organization";
        case AKILES_ORGANIZATION = "akiles_organization";
    }

    enum SystemType: string
    {
        case PTI_SITE = "pti_site";
        case AVIGILON_ALTA_ORG = "avigilon_alta_org";
        case SALTO_KS_SITE = "salto_ks_site";
        case SALTO_SPACE_SYSTEM = "salto_space_system";
        case BRIVO_ACCOUNT = "brivo_account";
        case HID_CREDENTIAL_MANAGER_ORGANIZATION = "hid_credential_manager_organization";
        case VISIONLINE_SYSTEM = "visionline_system";
        case ASSA_ABLOY_CREDENTIAL_SERVICE = "assa_abloy_credential_service";
        case LATCH_BUILDING = "latch_building";
        case DORMAKABA_COMMUNITY_SITE = "dormakaba_community_site";
        case DORMAKABA_AMBIANCE_SITE = "dormakaba_ambiance_site";
        case LEGIC_CONNECT_CREDENTIAL_SERVICE = "legic_connect_credential_service";
        case ASSA_ABLOY_VOSTIO = "assa_abloy_vostio";
        case ASSA_ABLOY_VOSTIO_CREDENTIAL_SERVICE = "assa_abloy_vostio_credential_service";
        case HOTEK_SITE = "hotek_site";
        case KISI_ORGANIZATION = "kisi_organization";
        case AKILES_ORGANIZATION = "akiles_organization";
    }
}

namespace Seam\Resources\AcsSystem\Errors {
    /**
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge), for example, if Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline.
     * This error might also occur if Seam Bridge is connected to the wrong [workspace](https://docs.seam.co/core-concepts/workspaces).
     * See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class SeamBridgeDisconnected extends \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): SeamBridgeDisconnected|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge), for example, if Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline.
     * See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class BridgeDisconnected extends \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(mixed $json): BridgeDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
                is_bridge_error: $json->is_bridge_error ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to the [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge) is functioning correctly and the Seam API can communicate with Seam Bridge, but the Seam API cannot connect to the on-premises [Visionline access control system](https://docs.seam.co/device-and-system-integration-guides/assa-abloy-visionline-access-control-system).
     * For example, the IP address of the on-premises access control system may be set incorrectly within the Seam [workspace](https://docs.seam.co/core-concepts/workspaces).
     * See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-visionline_instance_unreachable).
     */
    final class VisionlineInstanceUnreachable extends
        \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): VisionlineInstanceUnreachable|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the maximum number of users allowed for the site has been reached. This means that new access codes cannot be created. Contact Salto support to increase the user limit.
     */
    final class SaltoKsSubscriptionLimitExceeded extends
        \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsSubscriptionLimitExceeded|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that Seam's integration user does not have sufficient permissions on the provider's system backing this [access control system](https://docs.seam.co/low-level-apis/access-systems). Access cannot be managed until permissions are restored. See the error message for specifics, then either reauthorize the connected account in Seam or grant the integration user the required permissions in the provider's system.
     */
    final class InsufficientPermissions extends \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): InsufficientPermissions|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the [access control system](https://docs.seam.co/low-level-apis/access-systems) has been disconnected. See [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system) to resolve the issue.
     */
    final class AcsSystemDisconnected extends \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): AcsSystemDisconnected|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the login credentials are invalid. Reconnect the account using a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) to restore access.
     */
    final class AccountDisconnected extends \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(mixed $json): AccountDisconnected|null
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the [access control system](https://docs.seam.co/low-level-apis/access-systems) has lost its Salto KS certification. Contact [support](mailto:support@seam.co) to regain access.
     */
    final class SaltoKsCertificationExpired extends
        \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsCertificationExpired|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the access control system provider's service is temporarily unavailable. Seam will automatically retry and reconnect when the service becomes available again.
     */
    final class ProviderServiceUnavailable extends
        \Seam\Resources\AcsSystem\Errors
    {
        public static function from_json(
            mixed $json,
        ): ProviderServiceUnavailable|null {
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
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    enum ErrorCode: string
    {
        case SEAM_BRIDGE_DISCONNECTED = "seam_bridge_disconnected";
        case BRIDGE_DISCONNECTED = "bridge_disconnected";
        case VISIONLINE_INSTANCE_UNREACHABLE = "visionline_instance_unreachable";
        case SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED = "salto_ks_subscription_limit_exceeded";
        case INSUFFICIENT_PERMISSIONS = "insufficient_permissions";
        case ACS_SYSTEM_DISCONNECTED = "acs_system_disconnected";
        case ACCOUNT_DISCONNECTED = "account_disconnected";
        case SALTO_KS_CERTIFICATION_EXPIRED = "salto_ks_certification_expired";
        case PROVIDER_SERVICE_UNAVAILABLE = "provider_service_unavailable";
    }
}

namespace Seam\Resources\AcsSystem\Warnings {
    /**
     * Indicates that the Salto KS site has exceeded 80% of the maximum number of allowed users. Increase your subscription limit or delete some users from your site to rectify the issue.
     */
    final class SaltoKsSubscriptionLimitAlmostReached extends
        \Seam\Resources\AcsSystem\Warnings
    {
        public static function from_json(
            mixed $json,
        ): SaltoKsSubscriptionLimitAlmostReached|null {
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
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates the [access control system](https://docs.seam.co/low-level-apis/access-systems) time zone could not be determined because the reported physical location does not match the time zone configured on the physical [ACS entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    final class TimeZoneDoesNotMatchLocation extends
        \Seam\Resources\AcsSystem\Warnings
    {
        public static function from_json(
            mixed $json,
        ): TimeZoneDoesNotMatchLocation|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
                misconfigured_acs_entrance_ids: $json->misconfigured_acs_entrance_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
            /**
             * @var list<string>|null
             * @deprecated this field is deprecated.
             */
            public array|null $misconfigured_acs_entrance_ids = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the access control system requires additional setup before it can be fully operational. Follow the instructions in the warning message to complete the setup.
     */
    final class SetupRequired extends \Seam\Resources\AcsSystem\Warnings
    {
        public static function from_json(mixed $json): SetupRequired|null
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
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that Seam encountered an unexpected error while syncing this [access control system](https://docs.seam.co/low-level-apis/access-systems), so its users, credentials, and access groups may be out of date. Seam retries on every sync cycle and clears this warning once a sync succeeds; if it persists, contact [support](mailto:support@seam.co).
     */
    final class UnknownIssueWithAcsSystem extends
        \Seam\Resources\AcsSystem\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnknownIssueWithAcsSystem|null {
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
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\AcsSystem\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    enum WarningCode: string
    {
        case SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED = "salto_ks_subscription_limit_almost_reached";
        case TIME_ZONE_DOES_NOT_MATCH_LOCATION = "time_zone_does_not_match_location";
        case SETUP_REQUIRED = "setup_required";
        case UNKNOWN_ISSUE_WITH_ACS_SYSTEM = "unknown_issue_with_acs_system";
    }
}
