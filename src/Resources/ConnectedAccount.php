<?php

namespace Seam\Resources;

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
            account_type_display_name: $json->account_type_display_name ?? null,
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
                fn($e) => ConnectedAccountErrors::from_json($e),
                $json->errors ?? [],
            ),
            ical_feed_origin: $json->ical_feed_origin ?? null,
            ical_url: $json->ical_url ?? null,
            image_url: $json->image_url ?? null,
            time_zone: $json->time_zone ?? null,
            user_identifier: isset($json->user_identifier)
                ? ConnectedAccountUserIdentifier::from_json(
                    $json->user_identifier,
                )
                : null,
            warnings: array_map(
                fn($w) => ConnectedAccountWarnings::from_json($w),
                $json->warnings ?? [],
            ),
        );
    }

    public function __construct(
        public array|null $accepted_capabilities,
        public string|null $account_type,
        public string|null $account_type_display_name,
        public bool|null $automatically_manage_new_devices,
        public string|null $connected_account_id,
        public string|null $created_at,
        public mixed $custom_metadata,
        public string|null $customer_key,
        public string|null $default_checkin_time,
        public string|null $default_checkout_time,
        public string|null $display_name,
        public array $errors,
        public string|null $ical_feed_origin,
        public string|null $ical_url,
        public string|null $image_url,
        public string|null $time_zone,
        public ConnectedAccountUserIdentifier|null $user_identifier,
        public array $warnings,
    ) {}
}

class ConnectedAccountErrors
{
    public static function from_json(mixed $json): ConnectedAccountErrors|null
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
                ? ConnectedAccountSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata,
                )
                : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public bool|null $is_bridge_error,
        public bool|null $is_connected_account_error,
        public string|null $message,
        public ConnectedAccountSaltoKsMetadata|null $salto_ks_metadata,
    ) {}
}

class ConnectedAccountSaltoKsMetadata
{
    public static function from_json(
        mixed $json,
    ): ConnectedAccountSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => ConnectedAccountSites::from_json($s),
                $json->sites ?? [],
            ),
        );
    }

    public function __construct(public array $sites) {}
}

class ConnectedAccountSites
{
    public static function from_json(mixed $json): ConnectedAccountSites|null
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
        public string|null $site_id,
        public string|null $site_name,
        public int|null $site_user_subscription_limit,
        public int|null $subscribed_site_user_count,
    ) {}
}

class ConnectedAccountUserIdentifier
{
    public static function from_json(
        mixed $json,
    ): ConnectedAccountUserIdentifier|null {
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
        public string|null $api_url,
        public string|null $email,
        public bool|null $exclusive,
        public string|null $phone,
        public string|null $username,
    ) {}
}

class ConnectedAccountWarnings
{
    public static function from_json(mixed $json): ConnectedAccountWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? ConnectedAccountSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata,
                )
                : null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public ConnectedAccountSaltoKsMetadata|null $salto_ks_metadata,
        public string|null $warning_code,
    ) {}
}
