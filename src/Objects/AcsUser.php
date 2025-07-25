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
            acs_system_id: $json->acs_system_id,
            acs_user_id: $json->acs_user_id,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsUserErrors::from_json($e),
                $json->errors ?? [],
            ),
            is_managed: $json->is_managed,
            warnings: array_map(
                fn($w) => AcsUserWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id,
            access_schedule: isset($json->access_schedule)
                ? AcsUserAccessSchedule::from_json($json->access_schedule)
                : null,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            full_name: $json->full_name ?? null,
            hid_acs_system_id: $json->hid_acs_system_id ?? null,
            is_suspended: $json->is_suspended ?? null,
            pending_mutations: array_map(
                fn($p) => AcsUserPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            phone_number: $json->phone_number ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            last_successful_sync_at: $json->last_successful_sync_at ?? null,
            user_identity_email_address: $json->user_identity_email_address ??
                null,
            user_identity_full_name: $json->user_identity_full_name ?? null,
            user_identity_phone_number: $json->user_identity_phone_number ??
                null,
        );
    }

    public function __construct(
        public string $acs_system_id,
        public string $acs_user_id,
        public string $connected_account_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public bool $is_managed,
        public array $warnings,
        public string $workspace_id,
        public AcsUserAccessSchedule|null $access_schedule,
        public string|null $email,
        public string|null $email_address,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $full_name,
        public string|null $hid_acs_system_id,
        public bool|null $is_suspended,
        public array|null $pending_mutations,
        public string|null $phone_number,
        public string|null $user_identity_id,
        public string|null $last_successful_sync_at,
        public string|null $user_identity_email_address,
        public string|null $user_identity_full_name,
        public string|null $user_identity_phone_number,
    ) {}
}
