<?php

namespace Seam\Resources;

class InstantKey
{
    public static function from_json(mixed $json): InstantKey|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            client_session_id: $json->client_session_id ?? null,
            created_at: $json->created_at ?? null,
            customization: isset($json->customization)
                ? InstantKeyCustomization::from_json($json->customization)
                : null,
            customization_profile_id: $json->customization_profile_id ?? null,
            expires_at: $json->expires_at ?? null,
            instant_key_id: $json->instant_key_id ?? null,
            instant_key_url: $json->instant_key_url ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $client_session_id,
        public string|null $created_at,
        public InstantKeyCustomization|null $customization,
        public string|null $customization_profile_id,
        public string|null $expires_at,
        public string|null $instant_key_id,
        public string|null $instant_key_url,
        public string|null $user_identity_id,
        public string|null $workspace_id,
    ) {}
}

class InstantKeyCustomization
{
    public static function from_json(mixed $json): InstantKeyCustomization|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            logo_url: $json->logo_url ?? null,
            primary_color: $json->primary_color ?? null,
            secondary_color: $json->secondary_color ?? null,
        );
    }

    public function __construct(
        public string|null $logo_url,
        public string|null $primary_color,
        public string|null $secondary_color,
    ) {}
}
