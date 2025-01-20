<?php

namespace Seam\Objects;

class ConnectedAccountErrors
{
    public static function from_json(mixed $json): ConnectedAccountErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            error_code: $json->error_code,
            is_connected_account_error: $json->is_connected_account_error,
            message: $json->message,
            created_at: $json->created_at ?? null
        );
    }

    public function __construct(
        public string $error_code,
        public bool $is_connected_account_error,
        public string $message,
        public string|null $created_at
    ) {}
}
