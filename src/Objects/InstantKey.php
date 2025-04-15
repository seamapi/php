<?php

namespace Seam\Objects;

class InstantKey
{
    public static function from_json(mixed $json): InstantKey|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            client_session_id: $json->client_session_id,
            created_at: $json->created_at,
            expires_at: $json->expires_at,
            instant_key_id: $json->instant_key_id,
            instant_key_url: $json->instant_key_url,
            user_identity_id: $json->user_identity_id,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $client_session_id,
        public string $created_at,
        public string $expires_at,
        public string $instant_key_id,
        public string $instant_key_url,
        public string $user_identity_id,
        public string $workspace_id
    ) {
    }
}
