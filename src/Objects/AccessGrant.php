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
            location_ids: $json->location_ids,
            requested_access_methods: array_map(
                fn($r) => AccessGrantRequestedAccessMethods::from_json($r),
                $json->requested_access_methods ?? [],
            ),
            space_ids: $json->space_ids,
            user_identity_id: $json->user_identity_id,
            workspace_id: $json->workspace_id,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string $access_grant_id,
        public array $access_method_ids,
        public string $created_at,
        public string $display_name,
        public array $location_ids,
        public array $requested_access_methods,
        public array $space_ids,
        public string $user_identity_id,
        public string $workspace_id,
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}
