<?php

namespace Seam\Resources {
    /**
     * Represents a [connected account](https://docs.seam.co/core-concepts/connected-accounts). A connected account is an external third-party account to which your user has authorized Seam to get access, for example, an August account with a list of door locks.
     */
    class ConnectedAccount
    {
        public static function from_json(mixed $json): ConnectedAccount|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                accepted_capabilities: $json->accepted_capabilities ?? null,
                account_type_display_name: $json->account_type_display_name ??
                    null,
                automatically_manage_new_devices: $json->automatically_manage_new_devices ??
                    null,
                connected_account_id: $json->connected_account_id ?? null,
                custom_metadata: $json->custom_metadata ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ConnectedAccount\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ConnectedAccount\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                account_type: $json->account_type ?? null,
                created_at: $json->created_at ?? null,
                customer_key: $json->customer_key ?? null,
                default_checkin_time: $json->default_checkin_time ?? null,
                default_checkout_time: $json->default_checkout_time ?? null,
                ical_feed_origin: $json->ical_feed_origin ?? null,
                ical_url: $json->ical_url ?? null,
                image_url: $json->image_url ?? null,
                time_zone: $json->time_zone ?? null,
                user_identifier: isset($json->user_identifier)
                    ? \Seam\Resources\ConnectedAccount\UserIdentifier::from_json(
                        $json->user_identifier,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * List of capabilities that were accepted during the account connection process.
             *
             * @var list<string>|null
             */
            public array|null $accepted_capabilities,
            /**
             * Display name for the connected account type.
             */
            public string|null $account_type_display_name,
            /**
             * Indicates whether Seam should [import all new devices](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#automatically_manage_new_devices) for the connected account to make these devices available for management by the Seam API.
             */
            public bool|null $automatically_manage_new_devices,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id,
            /**
             * Set of key:value pairs. Adding custom metadata to a resource, such as a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews/attaching-custom-data-to-the-connect-webview), [connected account](https://docs.seam.co/core-concepts/connected-accounts/adding-custom-metadata-to-a-connected-account), or [device](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device), enables you to store custom information, like customer details or internal IDs from your application.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $custom_metadata,
            /**
             * Display name for the connected account.
             */
            public string|null $display_name,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\ConnectedAccount\Errors>
             */
            public array $errors,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\ConnectedAccount\Warnings>
             */
            public array $warnings,
            /**
             * Type of connected account.
             */
            public string|null $account_type = null,
            /**
             * Date and time at which the connected account was created.
             */
            public string|null $created_at = null,
            /**
             * Your unique key for the customer associated with this connected account.
             */
            public string|null $customer_key = null,
            /**
             * Default reservation check-in time for this connected account, as `HH:mm` (24-hour). Sourced from the connector configuration — set during the connect_webview for providers like Lodgify whose API does not expose check-in times.
             */
            public string|null $default_checkin_time = null,
            /**
             * Default reservation check-out time for this connected account, as `HH:mm` (24-hour). Sourced from the connector configuration.
             */
            public string|null $default_checkout_time = null,
            /**
             * For iCal connected accounts, the platform that produced the feed (for example, `airbnb`, `vrbo`, or `booking`), or `unknown` when it could not be determined. Intended for rendering the source platform's logo.
             */
            public string|null $ical_feed_origin = null,
            /**
             * For iCal connected accounts, the feed URL for the connection. Sourced from the connector configuration.
             */
            public string|null $ical_url = null,
            /**
             * Logo URL for the connected account provider.
             */
            public string|null $image_url = null,
            /**
             * IANA time zone (e.g. America/Los_Angeles) for this connected account. Sourced from the connector configuration.
             */
            public string|null $time_zone = null,
            /**
             * User identifier associated with the connected account.
             *
             * @deprecated Use `display_name` instead.
             */
            public \Seam\Resources\ConnectedAccount\UserIdentifier|null $user_identifier = null,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount {
    /**
     * Errors associated with the connected account. Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\ConnectedAccount\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\ConnectedAccount\Errors\ErrorCode::ACCOUNT_DISCONNECTED
                    => \Seam\Resources\ConnectedAccount\Errors\AccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Errors\ErrorCode::BRIDGE_DISCONNECTED
                    => \Seam\Resources\ConnectedAccount\Errors\BridgeDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Errors\ErrorCode::SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED
                    => \Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Errors\ErrorCode::DORMAKABA_SITES_DISCONNECTED
                    => \Seam\Resources\ConnectedAccount\Errors\DormakabaSitesDisconnected::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    error_code: $json->error_code ?? null,
                    message: $json->message ?? null,
                    is_bridge_error: $json->is_bridge_error ?? null,
                    is_connected_account_error: $json->is_connected_account_error ??
                        null,
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            public bool|null $is_connected_account_error = null,
        ) {}
    }

    /**
     * User identifier associated with the connected account.
     *
     * @deprecated Use `display_name` instead.
     */
    class UserIdentifier
    {
        public static function from_json(mixed $json): UserIdentifier|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                api_url: $json->api_url ?? null,
                email: $json->email ?? null,
                exclusive: $json->exclusive ?? null,
                phone: $json->phone ?? null,
                username: $json->username ?? null,
            );
        }

        public function __construct(
            /**
             * API URL for the user identifier associated with the connected account.
             */
            public string|null $api_url = null,
            /**
             * Email address of the user identifier associated with the connected account.
             */
            public string|null $email = null,
            /**
             * Indicates whether the user identifier associated with the connected account is exclusive.
             */
            public bool|null $exclusive = null,
            /**
             * Phone number of the user identifier associated with the connected account.
             */
            public string|null $phone = null,
            /**
             * Username of the user identifier associated with the connected account.
             */
            public string|null $username = null,
        ) {}
    }

    /**
     * Warnings associated with the connected account. Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\ConnectedAccount\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::SCHEDULED_MAINTENANCE_WINDOW
                    => \Seam\Resources\ConnectedAccount\Warnings\ScheduledMaintenanceWindow::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::UNKNOWN_ISSUE_WITH_CONNECTED_ACCOUNT
                    => \Seam\Resources\ConnectedAccount\Warnings\UnknownIssueWithConnectedAccount::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED
                    => \Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::ACCOUNT_REAUTHORIZATION_REQUESTED
                    => \Seam\Resources\ConnectedAccount\Warnings\AccountReauthorizationRequested::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\ConnectedAccount\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::PROVIDER_SERVICE_UNAVAILABLE
                    => \Seam\Resources\ConnectedAccount\Warnings\ProviderServiceUnavailable::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::SETUP_REQUIRED
                    => \Seam\Resources\ConnectedAccount\Warnings\SetupRequired::from_json(
                    $json,
                ),
                \Seam\Resources\ConnectedAccount\Warnings\WarningCode::DORMAKABA_SITES_UNAPPROVED
                    => \Seam\Resources\ConnectedAccount\Warnings\DormakabaSitesUnapproved::from_json(
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Errors {
    /**
     * Indicates that the account is disconnected.
     */
    final class AccountDisconnected extends
        \Seam\Resources\ConnectedAccount\Errors
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
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            bool|null $is_connected_account_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                is_bridge_error: $is_bridge_error,
                is_connected_account_error: $is_connected_account_error,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the Seam API cannot communicate with [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge), for example, if the Seam Bridge executable has stopped or if the computer running the Seam Bridge executable is offline. See also [Troubleshooting Your Access Control System](https://docs.seam.co/low-level-apis/access-systems/troubleshooting-your-access-control-system#acs_system-errors-seam_bridge_disconnected).
     */
    final class BridgeDisconnected extends
        \Seam\Resources\ConnectedAccount\Errors
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
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            bool|null $is_connected_account_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                is_bridge_error: $is_bridge_error,
                is_connected_account_error: $is_connected_account_error,
                message: $message,
            );
        }
    }

    /**
     * Indicates that the maximum number of users allowed for the site has been reached. This means that new access codes cannot be created. Contact Salto support to increase the user limit.
     */
    final class SaltoKsSubscriptionLimitExceeded extends
        \Seam\Resources\ConnectedAccount\Errors
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
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? \Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Salto KS metadata associated with the connected account that has an error.
             */
            public \Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded\SaltoKsMetadata|null $salto_ks_metadata,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            bool|null $is_connected_account_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                is_bridge_error: $is_bridge_error,
                is_connected_account_error: $is_connected_account_error,
                message: $message,
            );
        }
    }

    /**
     * Indicates that one or more dormakaba sites associated with the connected account could not be connected. Contact dormakaba support.
     */
    final class DormakabaSitesDisconnected extends
        \Seam\Resources\ConnectedAccount\Errors
    {
        public static function from_json(
            mixed $json,
        ): DormakabaSitesDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            bool|null $is_bridge_error = null,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            bool|null $is_connected_account_error = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                error_code: $error_code,
                is_bridge_error: $is_bridge_error,
                is_connected_account_error: $is_connected_account_error,
                message: $message,
            );
        }
    }

    enum ErrorCode: string
    {
        case ACCOUNT_DISCONNECTED = "account_disconnected";
        case BRIDGE_DISCONNECTED = "bridge_disconnected";
        case SALTO_KS_SUBSCRIPTION_LIMIT_EXCEEDED = "salto_ks_subscription_limit_exceeded";
        case DORMAKABA_SITES_DISCONNECTED = "dormakaba_sites_disconnected";
    }
}

namespace Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded {
    /**
     * Salto KS metadata associated with the connected account that has an error.
     */
    class SaltoKsMetadata
    {
        public static function from_json(mixed $json): SaltoKsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                sites: array_map(
                    fn(
                        $s,
                    ) => \Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded\SaltoKsMetadata\Sites::from_json(
                        $s,
                    ),
                    $json->sites ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Salto sites associated with the connected account that has an error.
             *
             * @var list<\Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded\SaltoKsMetadata\Sites>|null
             */
            public array|null $sites = null,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Errors\SaltoKsSubscriptionLimitExceeded\SaltoKsMetadata {
    /**
     * Salto sites associated with the connected account that has an error.
     */
    class Sites
    {
        public static function from_json(mixed $json): Sites|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
                site_user_subscription_limit: $json->site_user_subscription_limit ??
                    null,
                subscribed_site_user_count: $json->subscribed_site_user_count ??
                    null,
            );
        }

        public function __construct(
            /**
             * ID of a Salto site associated with the connected account that has an error.
             */
            public string|null $site_id = null,
            /**
             * Name of a Salto site associated with the connected account that has an error.
             */
            public string|null $site_name = null,
            /**
             * Subscription limit of site users for a Salto site associated with the connected account that has an error.
             */
            public int|null $site_user_subscription_limit = null,
            /**
             * Count of subscribed site users for a Salto site associated with the connected account that has an error.
             */
            public int|null $subscribed_site_user_count = null,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Warnings {
    /**
     * Indicates that scheduled downtime is planned for the connected account.
     */
    final class ScheduledMaintenanceWindow extends
        \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ScheduledMaintenanceWindow|null {
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that an unknown issue occurred while syncing the state of the connected account with the provider. This issue may affect the proper functioning of one or more resources in the account.
     */
    final class UnknownIssueWithConnectedAccount extends
        \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(
            mixed $json,
        ): UnknownIssueWithConnectedAccount|null {
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that the Salto KS site has exceeded 80% of the maximum number of allowed users. Increase your subscription limit or delete some users from your site.
     */
    final class SaltoKsSubscriptionLimitAlmostReached extends
        \Seam\Resources\ConnectedAccount\Warnings
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
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? \Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
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
             * Salto KS metadata associated with the connected account that has a warning.
             */
            public \Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached\SaltoKsMetadata|null $salto_ks_metadata,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that the Connected Account requires reauthorization using a new Connect Webview. The account is still connected, but cannot access new features. Delaying reauthorization too long will eventually cause the Connected Account to become disconnected.
     */
    final class AccountReauthorizationRequested extends
        \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(
            mixed $json,
        ): AccountReauthorizationRequested|null {
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that the connected account is currently being deleted. All devices, access codes, and other resources associated with this account are in the process of being removed from Seam.
     */
    final class BeingDeleted extends \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that the connected account's provider service is temporarily unavailable. Seam will automatically retry and reconnect when the service becomes available again.
     */
    final class ProviderServiceUnavailable extends
        \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(
            mixed $json,
        ): ProviderServiceUnavailable|null {
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that the connected account requires additional setup before it can be fully operational. Follow the instructions in the warning message to complete the setup.
     */
    final class SetupRequired extends \Seam\Resources\ConnectedAccount\Warnings
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
     * Indicates that one or more dormakaba sites associated with the connected account are not approved. Contact support@getseam.com to finish setting up your account.
     */
    final class DormakabaSitesUnapproved extends
        \Seam\Resources\ConnectedAccount\Warnings
    {
        public static function from_json(
            mixed $json,
        ): DormakabaSitesUnapproved|null {
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
             * @var value-of<\Seam\Resources\ConnectedAccount\Warnings\WarningCode>|string|null
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
        case SCHEDULED_MAINTENANCE_WINDOW = "scheduled_maintenance_window";
        case UNKNOWN_ISSUE_WITH_CONNECTED_ACCOUNT = "unknown_issue_with_connected_account";
        case SALTO_KS_SUBSCRIPTION_LIMIT_ALMOST_REACHED = "salto_ks_subscription_limit_almost_reached";
        case ACCOUNT_REAUTHORIZATION_REQUESTED = "account_reauthorization_requested";
        case BEING_DELETED = "being_deleted";
        case PROVIDER_SERVICE_UNAVAILABLE = "provider_service_unavailable";
        case SETUP_REQUIRED = "setup_required";
        case DORMAKABA_SITES_UNAPPROVED = "dormakaba_sites_unapproved";
    }
}

namespace Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached {
    /**
     * Salto KS metadata associated with the connected account that has a warning.
     */
    class SaltoKsMetadata
    {
        public static function from_json(mixed $json): SaltoKsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                sites: array_map(
                    fn(
                        $s,
                    ) => \Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached\SaltoKsMetadata\Sites::from_json(
                        $s,
                    ),
                    $json->sites ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Salto sites associated with the connected account that has a warning.
             *
             * @var list<\Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached\SaltoKsMetadata\Sites>|null
             */
            public array|null $sites = null,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Warnings\SaltoKsSubscriptionLimitAlmostReached\SaltoKsMetadata {
    /**
     * Salto sites associated with the connected account that has a warning.
     */
    class Sites
    {
        public static function from_json(mixed $json): Sites|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
                site_user_subscription_limit: $json->site_user_subscription_limit ??
                    null,
                subscribed_site_user_count: $json->subscribed_site_user_count ??
                    null,
            );
        }

        public function __construct(
            /**
             * ID of a Salto site associated with the connected account that has a warning.
             */
            public string|null $site_id = null,
            /**
             * Name of a Salto site associated with the connected account that has a warning.
             */
            public string|null $site_name = null,
            /**
             * Subscription limit of site users for a Salto site associated with the connected account that has a warning.
             */
            public int|null $site_user_subscription_limit = null,
            /**
             * Count of subscribed site users for a Salto site associated with the connected account that has a warning.
             */
            public int|null $subscribed_site_user_count = null,
        ) {}
    }
}
