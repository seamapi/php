<?php

namespace Seam\Objects;

class AcsUserWarnings
{
    public static function from_json(mixed $json): AcsUserWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code
    ) {
    }
}
