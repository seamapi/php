<?php

namespace Seam\Objects;

class ConnectedAccountUserIdentifier
{
    
    public static function from_json(mixed $json): ConnectedAccountUserIdentifier|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            username: $json->username ?? null,
            api_url: $json->api_url ?? null,
            email: $json->email ?? null,
            phone: $json->phone ?? null,
            exclusive: $json->exclusive ?? null,
        );
    }
  

    
    public function __construct(
        public string | null $username,
        public string | null $api_url,
        public string | null $email,
        public string | null $phone,
        public bool | null $exclusive,
    ) {
    }
  
}
