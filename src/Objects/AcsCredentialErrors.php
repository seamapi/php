<?php

namespace Seam\Objects;

class AcsCredentialErrors
{
    public static function from_json(mixed $json): AcsCredentialErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $error_code,
        public string|null $message,
    ) {}
}
