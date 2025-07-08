<?php

namespace Seam\Objects;

class UserIdentityErrors
{
    public static function from_json(mixed $json): UserIdentityErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(created_at: $json->created_at, message: $json->message);
    }

    public function __construct(
        public string $created_at,
        public string $message
    ) {}
}
