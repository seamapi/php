<?php

namespace Seam\Objects;

class PhoneSessionUserIdentity
{
    public static function from_json(mixed $json): PhoneSessionUserIdentity|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_user_ids: $json->acs_user_ids ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            email_address: $json->email_address ?? null,
            errors: array_map(
                fn($e) => PhoneSessionErrors::from_json($e),
                $json->errors ?? [],
            ),
            full_name: $json->full_name ?? null,
            phone_number: $json->phone_number ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            user_identity_key: $json->user_identity_key ?? null,
            warnings: array_map(
                fn($w) => PhoneSessionWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public array|null $acs_user_ids,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $email_address,
        public array $errors,
        public string|null $full_name,
        public string|null $phone_number,
        public string|null $user_identity_id,
        public string|null $user_identity_key,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
