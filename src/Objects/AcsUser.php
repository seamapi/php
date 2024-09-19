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
            access_schedule: isset($json->access_schedule)
                ? AcsUserAccessSchedule::from_json($json->access_schedule)
                : null,
            acs_system_id: $json->acs_system_id,
            acs_user_id: $json->acs_user_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            errors: $json->errors,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            full_name: $json->full_name ?? null,
            hid_acs_system_id: $json->hid_acs_system_id ?? null,
            is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                null,
            is_managed: $json->is_managed,
            is_suspended: $json->is_suspended,
            latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                null,
            phone_number: $json->phone_number ?? null,
            user_identity_email_address: $json->user_identity_email_address ??
                null,
            user_identity_full_name: $json->user_identity_full_name ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            user_identity_phone_number: $json->user_identity_phone_number ??
                null,
            warnings: $json->warnings,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public AcsUserAccessSchedule|null $access_schedule,
        public string $acs_system_id,
        public string $acs_user_id,
        public string $created_at,
        public string $display_name,
        public string|null $email,
        public string|null $email_address,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $full_name,
        public string|null $hid_acs_system_id,
        public bool|null $is_latest_desired_state_synced_with_provider,
        public bool $is_managed,
        public bool $is_suspended,
        public string|null $latest_desired_state_synced_with_provider_at,
        public string|null $phone_number,
        public string|null $user_identity_email_address,
        public string|null $user_identity_full_name,
        public string|null $user_identity_id,
        public string|null $user_identity_phone_number,
        public array $warnings,
        public string $workspace_id
    ) {
    }
}
