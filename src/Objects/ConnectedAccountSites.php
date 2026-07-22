<?php

namespace Seam\Objects;

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
        public float|null $site_user_subscription_limit,
        public float|null $subscribed_site_user_count,
    ) {}
}
