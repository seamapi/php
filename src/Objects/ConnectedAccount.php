<?php

namespace Seam\Objects;

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
