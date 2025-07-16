<?php

namespace Seam\Objects;

class UserIdentity
{
    public static function from_json(mixed $json): UserIdentity|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_user_ids: $json->acs_user_ids,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => UserIdentityErrors::from_json($e),
                $json->errors ?? []
            ),
            user_identity_id: $json->user_identity_id,
            warnings: array_map(
                fn($w) => UserIdentityWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id,
            email_address: $json->email_address ?? null,
            full_name: $json->full_name ?? null,
            phone_number: $json->phone_number ?? null,
            user_identity_key: $json->user_identity_key ?? null
        );
    }

    public function __construct(
        public array $acs_user_ids,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public string $user_identity_id,
        public array $warnings,
        public string $workspace_id,
        public string|null $email_address,
        public string|null $full_name,
        public string|null $phone_number,
        public string|null $user_identity_key
    ) {}
}
