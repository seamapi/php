<?php

namespace Seam\Objects;

class ConnectedAccountUserIdentifier
{
    public static function from_json(
        mixed $json
    ): ConnectedAccountUserIdentifier|null {
        if (!$json) {
            return null;
        }
        return new self(
            api_url: $json->api_url ?? null,
            email: $json->email ?? null,
            exclusive: $json->exclusive ?? null,
            phone: $json->phone ?? null,
            username: $json->username ?? null
        );
    }

    public function __construct(
        public string|null $api_url,
        public string|null $email,
        public bool|null $exclusive,
        public string|null $phone,
        public string|null $username
    ) {
    }
}
