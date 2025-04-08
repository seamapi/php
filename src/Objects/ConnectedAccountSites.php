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
            site_id: $json->site_id,
            site_name: $json->site_name,
            site_user_subscription_limit: $json->site_user_subscription_limit,
            subscribed_site_user_count: $json->subscribed_site_user_count
        );
    }

    public function __construct(
        public string $site_id,
        public string $site_name,
        public mixed $site_user_subscription_limit,
        public mixed $subscribed_site_user_count
    ) {
    }
}
