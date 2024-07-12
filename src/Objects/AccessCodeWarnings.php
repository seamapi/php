<?php

namespace Seam\Objects;

class AccessCodeWarnings
{
    public static function from_json(mixed $json): AccessCodeWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message,
            warning_code: $json->warning_code,
            created_at: $json->created_at ?? null
        );
    }

    public function __construct(
        public string $message,
        public string $warning_code,
        public string|null $created_at
    ) {
    }
}
