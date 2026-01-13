<?php

namespace Seam\Objects;

class AccessGrant
{
    public static function from_json(mixed $json): AccessGrant|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_grant_id: $json->access_grant_id,
            access_method_ids: $json->access_method_ids,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AccessGrantErrors::from_json($e),
                $json->errors ?? [],
            ),
            location_ids: $json->location_ids,
            requested_access_methods: array_map(
                fn($r) => AccessGrantRequestedAccessMethods::from_json($r),
                $json->requested_access_methods ?? [],
            ),
            space_ids: $json->space_ids,
            starts_at: $json->starts_at,
            user_identity_id: $json->user_identity_id,
            warnings: array_map(
                fn($w) => AccessGrantWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id,
            access_grant_key: $json->access_grant_key ?? null,
            client_session_token: $json->client_session_token ?? null,
            customization_profile_id: $json->customization_profile_id ?? null,
            instant_key_url: $json->instant_key_url ?? null,
            reservation_key: $json->reservation_key ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
        );
    }

    public function __construct(
        public string $access_grant_id,
        public array $access_method_ids,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public array $location_ids,
        public array $requested_access_methods,
        public array $space_ids,
        public string $starts_at,
        public string $user_identity_id,
        public array $warnings,
        public string $workspace_id,
        public string|null $access_grant_key,
        public string|null $client_session_token,
        public string|null $customization_profile_id,
        public string|null $instant_key_url,
        public string|null $reservation_key,
        public string|null $ends_at,
        public string|null $name,
    ) {}
}
