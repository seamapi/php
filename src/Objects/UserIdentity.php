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
            created_at: $json->created_at,
            display_name: $json->display_name,
            user_identity_id: $json->user_identity_id,
            workspace_id: $json->workspace_id,
            email_address: $json->email_address ?? null,
            full_name: $json->full_name ?? null,
            phone_number: $json->phone_number ?? null,
            user_identity_key: $json->user_identity_key ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public string $display_name,
        public string $user_identity_id,
        public string $workspace_id,
        public string|null $email_address,
        public string|null $full_name,
        public string|null $phone_number,
        public string|null $user_identity_key
    ) {
    }
}
