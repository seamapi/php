<?php

namespace Seam\Objects;

class PhoneSessionErrors
{
    public static function from_json(mixed $json): PhoneSessionErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            error_code: $json->error_code,
            message: $json->message,
            created_at: $json->created_at ?? null,
        );
    }

    public function __construct(
        public string $error_code,
        public string $message,
        public string|null $created_at,
    ) {}
}
