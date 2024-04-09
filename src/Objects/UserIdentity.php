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
            user_identity_id: $json->user_identity_id,
            user_identity_key: $json->user_identity_key ?? null,
            email_address: $json->email_address ?? null,
            phone_number: $json->phone_number ?? null,
            display_name: $json->display_name,
            full_name: $json->full_name ?? null,
            created_at: $json->created_at,
            workspace_id: $json->workspace_id,
        );
    }
  

    
    public function __construct(
        public string $user_identity_id,
        public string | null $user_identity_key,
        public string | null $email_address,
        public string | null $phone_number,
        public string $display_name,
        public string | null $full_name,
        public string $created_at,
        public string $workspace_id,
    ) {
    }
  
}
