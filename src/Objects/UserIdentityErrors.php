<?php

namespace Seam\Objects;

class UserIdentityErrors
{
    public static function from_json(mixed $json): UserIdentityErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}
