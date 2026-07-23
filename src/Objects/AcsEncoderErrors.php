<?php

namespace Seam\Objects;

class AcsEncoderErrors
{
    public static function from_json(mixed $json): AcsEncoderErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}
