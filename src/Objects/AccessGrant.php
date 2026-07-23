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
            access_grant_id: $json->access_grant_id ?? null,
            access_grant_key: $json->access_grant_key ?? null,
            access_method_ids: $json->access_method_ids ?? null,
            client_session_token: $json->client_session_token ?? null,
            created_at: $json->created_at ?? null,
            customization_profile_id: $json->customization_profile_id ?? null,
            display_name: $json->display_name ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
                fn($e) => AccessGrantErrors::from_json($e),
                $json->errors ?? [],
            ),
            instant_key_url: $json->instant_key_url ?? null,
            location_ids: $json->location_ids ?? null,
            name: $json->name ?? null,
            pending_mutations: array_map(
                fn($p) => AccessGrantPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            requested_access_methods: array_map(
                fn($r) => AccessGrantRequestedAccessMethods::from_json($r),
                $json->requested_access_methods ?? [],
            ),
            reservation_key: $json->reservation_key ?? null,
            space_ids: $json->space_ids ?? null,
            starts_at: $json->starts_at ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            warnings: array_map(
                fn($w) => AccessGrantWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $access_grant_id,
        public string|null $access_grant_key,
        public array|null $access_method_ids,
        public string|null $client_session_token,
        public string|null $created_at,
        public string|null $customization_profile_id,
        public string|null $display_name,
        public string|null $ends_at,
        public array $errors,
        public string|null $instant_key_url,
        public array|null $location_ids,
        public string|null $name,
        public array $pending_mutations,
        public array $requested_access_methods,
        public string|null $reservation_key,
        public array|null $space_ids,
        public string|null $starts_at,
        public string|null $user_identity_id,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
