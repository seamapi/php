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
            acs_system_id: $json->acs_system_id,
            acs_user_id: $json->acs_user_id,
            created_at: $json->created_at,
            error_code: $json->error_code,
            message: $json->message
        );
    }

    public function __construct(
        public string $acs_system_id,
        public string $acs_user_id,
        public string $created_at,
        public string $error_code,
        public string $message
    ) {
    }
}
