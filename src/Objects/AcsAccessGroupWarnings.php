<?php

namespace Seam\Objects;

class AcsAccessGroupWarnings
{
    public static function from_json(mixed $json): AcsAccessGroupWarnings|null
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
