<?php

namespace Seam\Objects;

class AcsUser
{
    public static function from_json(mixed $json): AcsUser|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_user_id: $json->acs_user_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            workspace_id: $json->workspace_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            is_suspended: $json->is_suspended ?? null,
            full_name: $json->full_name ?? null,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            phone_number: $json->phone_number ?? null
        );
    }

    public function __construct(
        public string|null $acs_user_id,
        public string|null $acs_system_id,
        public string|null $workspace_id,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public bool|null $is_suspended,
        public string|null $full_name,
        public string|null $email,
        public string|null $email_address,
        public string|null $phone_number
    ) {
    }
}
