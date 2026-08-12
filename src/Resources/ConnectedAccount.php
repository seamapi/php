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
                account_type: $json->account_type ?? null,
                account_type_display_name: $json->account_type_display_name ??
                    null,
                automatically_manage_new_devices: $json->automatically_manage_new_devices ??
                    null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                custom_metadata: $json->custom_metadata ?? null,
                customer_key: $json->customer_key ?? null,
                default_checkin_time: $json->default_checkin_time ?? null,
                default_checkout_time: $json->default_checkout_time ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => ConnectedAccount\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                ical_feed_origin: $json->ical_feed_origin ?? null,
                ical_url: $json->ical_url ?? null,
                image_url: $json->image_url ?? null,
                time_zone: $json->time_zone ?? null,
                user_identifier: isset($json->user_identifier)
                    ? ConnectedAccount\UserIdentifier::from_json(
                        $json->user_identifier,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => ConnectedAccount\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * List of capabilities that were accepted during the account connection process.
             */
            public array|null $accepted_capabilities,
            /**
             * Type of connected account.
             */
            public string|null $account_type,
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
             * Date and time at which the connected account was created.
             */
            public string|null $created_at,
            /**
             * Set of key:value pairs. Adding custom metadata to a resource, such as a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews/attaching-custom-data-to-the-connect-webview), [connected account](https://docs.seam.co/core-concepts/connected-accounts/adding-custom-metadata-to-a-connected-account), or [device](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device), enables you to store custom information, like customer details or internal IDs from your application.
             */
            public mixed $custom_metadata,
            /**
             * Your unique key for the customer associated with this connected account.
             */
            public string|null $customer_key,
            /**
             * Default reservation check-in time for this connected account, as `HH:mm` (24-hour). Sourced from the connector configuration — set during the connect_webview for providers like Lodgify whose API does not expose check-in times.
             */
            public string|null $default_checkin_time,
            /**
             * Default reservation check-out time for this connected account, as `HH:mm` (24-hour). Sourced from the connector configuration.
             */
            public string|null $default_checkout_time,
            /**
             * Display name for the connected account.
             */
            public string|null $display_name,
            /**
             * Errors associated with the connected account.
             */
            public array $errors,
            /**
             * For iCal connected accounts, the platform that produced the feed (for example, `airbnb`, `vrbo`, or `booking`), or `unknown` when it could not be determined. Intended for rendering the source platform's logo.
             */
            public string|null $ical_feed_origin,
            /**
             * For iCal connected accounts, the feed URL for the connection. Sourced from the connector configuration.
             */
            public string|null $ical_url,
            /**
             * Logo URL for the connected account provider.
             */
            public string|null $image_url,
            /**
             * IANA time zone (e.g. America/Los_Angeles) for this connected account. Sourced from the connector configuration.
             */
            public string|null $time_zone,
            /**
             * User identifier associated with the connected account.
             *
             * @deprecated Use `display_name` instead.
             */
            public ConnectedAccount\UserIdentifier|null $user_identifier,
            /**
             * Warnings associated with the connected account.
             */
            public array $warnings,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount {
    /**
     * Errors associated with the connected account.
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
                is_bridge_error: $json->is_bridge_error ?? null,
                is_connected_account_error: $json->is_connected_account_error ??
                    null,
                message: $json->message ?? null,
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? Errors\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
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
             * Indicates whether the error is related to [Seam Bridge](https://docs.seam.co/capability-guides/seam-bridge).
             */
            public bool|null $is_bridge_error,
            /**
             * Indicates whether the error is related specifically to the connected account.
             */
            public bool|null $is_connected_account_error,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Salto KS metadata associated with the connected account that has an error.
             */
            public Errors\SaltoKsMetadata|null $salto_ks_metadata,
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
            public string|null $api_url,
            /**
             * Email address of the user identifier associated with the connected account.
             */
            public string|null $email,
            /**
             * Indicates whether the user identifier associated with the connected account is exclusive.
             */
            public bool|null $exclusive,
            /**
             * Phone number of the user identifier associated with the connected account.
             */
            public string|null $phone,
            /**
             * Username of the user identifier associated with the connected account.
             */
            public string|null $username,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
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
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? Warnings\SaltoKsMetadata::from_json(
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
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Salto KS metadata associated with the connected account that has a warning.
             */
            public Warnings\SaltoKsMetadata|null $salto_ks_metadata,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Errors {
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
                    fn($s) => SaltoKsMetadata\Sites::from_json($s),
                    $json->sites ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Salto sites associated with the connected account that has an error.
             */
            public array $sites,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Errors\SaltoKsMetadata {
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
            public string|null $site_id,
            /**
             * Name of a Salto site associated with the connected account that has an error.
             */
            public string|null $site_name,
            /**
             * Subscription limit of site users for a Salto site associated with the connected account that has an error.
             */
            public int|null $site_user_subscription_limit,
            /**
             * Count of subscribed site users for a Salto site associated with the connected account that has an error.
             */
            public int|null $subscribed_site_user_count,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Warnings {
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
                    fn($s) => SaltoKsMetadata\Sites::from_json($s),
                    $json->sites ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Salto sites associated with the connected account that has a warning.
             */
            public array $sites,
        ) {}
    }
}

namespace Seam\Resources\ConnectedAccount\Warnings\SaltoKsMetadata {
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
            public string|null $site_id,
            /**
             * Name of a Salto site associated with the connected account that has a warning.
             */
            public string|null $site_name,
            /**
             * Subscription limit of site users for a Salto site associated with the connected account that has a warning.
             */
            public int|null $site_user_subscription_limit,
            /**
             * Count of subscribed site users for a Salto site associated with the connected account that has a warning.
             */
            public int|null $subscribed_site_user_count,
        ) {}
    }
}
