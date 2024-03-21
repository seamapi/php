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
            acs_user_id: $json->acs_user_id,
            acs_system_id: $json->acs_system_id,
            hid_acs_system_id: $json->hid_acs_system_id ?? null,
            workspace_id: $json->workspace_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ?? null,
            is_suspended: $json->is_suspended,
            access_schedule: isset($json->access_schedule) ? AcsUserAccessSchedule::from_json($json->access_schedule) : null,
            user_identity_id: $json->user_identity_id ?? null,
            user_identity_email_address: $json->user_identity_email_address ?? null,
            user_identity_phone_number: $json->user_identity_phone_number ?? null,
            full_name: $json->full_name ?? null,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            phone_number: $json->phone_number ?? null,
        );
    }
  

    
    public function __construct(
        public string $acs_user_id,
        public string $acs_system_id,
        public string | null $hid_acs_system_id,
        public string $workspace_id,
        public string $created_at,
        public string $display_name,
        public string | null $external_type,
        public string | null $external_type_display_name,
        public bool $is_suspended,
        public AcsUserAccessSchedule | null $access_schedule,
        public string | null $user_identity_id,
        public string | null $user_identity_email_address,
        public string | null $user_identity_phone_number,
        public string | null $full_name,
        public string | null $email,
        public string | null $email_address,
        public string | null $phone_number,
    ) {
    }
  
}
