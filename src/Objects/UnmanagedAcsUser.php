<?php

namespace Seam\Objects;

class UnmanagedAcsUser
{
    public static function from_json(mixed $json): UnmanagedAcsUser|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_schedule: isset($json->access_schedule)
                ? UnmanagedAcsUserAccessSchedule::from_json(
                    $json->access_schedule,
                )
                : null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            email: $json->email ?? null,
            email_address: $json->email_address ?? null,
            errors: array_map(
                fn($e) => UnmanagedAcsUserErrors::from_json($e),
                $json->errors ?? [],
            ),
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            full_name: $json->full_name ?? null,
            hid_acs_system_id: $json->hid_acs_system_id ?? null,
            is_managed: $json->is_managed ?? null,
            is_suspended: $json->is_suspended ?? null,
            last_successful_sync_at: $json->last_successful_sync_at ?? null,
            pending_mutations: array_map(
                fn($p) => UnmanagedAcsUserPendingMutations::from_json($p),
                $json->pending_mutations ?? [],
            ),
            phone_number: $json->phone_number ?? null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? UnmanagedAcsUserSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata,
                )
                : null,
            user_identity_email_address: $json->user_identity_email_address ??
                null,
            user_identity_full_name: $json->user_identity_full_name ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            user_identity_phone_number: $json->user_identity_phone_number ??
                null,
            warnings: array_map(
                fn($w) => UnmanagedAcsUserWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public UnmanagedAcsUserAccessSchedule|null $access_schedule,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $email,
        public string|null $email_address,
        public array $errors,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $full_name,
        public string|null $hid_acs_system_id,
        public bool|null $is_managed,
        public bool|null $is_suspended,
        public string|null $last_successful_sync_at,
        public array $pending_mutations,
        public string|null $phone_number,
        public UnmanagedAcsUserSaltoSpaceMetadata|null $salto_space_metadata,
        public string|null $user_identity_email_address,
        public string|null $user_identity_full_name,
        public string|null $user_identity_id,
        public string|null $user_identity_phone_number,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
