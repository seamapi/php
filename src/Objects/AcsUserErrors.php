<?php

namespace Seam\Objects;

class AcsUserErrors
{
    public static function from_json(mixed $json): AcsUserErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            error_code: $json->error_code,
            message: $json->message
        );
    }

    public function __construct(
        public string $created_at,
        public string $error_code,
        public string $message
    ) {
    }
}
