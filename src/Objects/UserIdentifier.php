<?php

namespace Seam\Objects;

class UserIdentifier
{
    public static function from_json(mixed $json): UserIdentifier|null
    {
        if (!$json) {
            return null;
        }
        return new self (
            email: $json->email ?? null,
            phone: $json->phone ?? null
        );
    }

    public function __construct(
        public string|null $email,
        public string|null $phone
    ) {
    }
}
