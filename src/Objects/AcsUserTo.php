<?php

namespace Seam\Objects;

class AcsUserTo
{
    public static function from_json(mixed $json): AcsUserTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            email_address: $json->email_address ?? null,
            ends_at: $json->ends_at ?? null,
            full_name: $json->full_name ?? null,
            is_suspended: $json->is_suspended ?? null,
            phone_number: $json->phone_number ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $acs_access_group_id,
        public string|null $acs_credential_id,
        public string|null $email_address,
        public string|null $ends_at,
        public string|null $full_name,
        public bool|null $is_suspended,
        public string|null $phone_number,
        public string|null $starts_at,
    ) {}
}
