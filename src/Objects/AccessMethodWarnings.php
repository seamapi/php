<?php

namespace Seam\Objects;

class AccessMethodWarnings
{
    public static function from_json(mixed $json): AccessMethodWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code,
            original_access_method_id: $json->original_access_method_id ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code,
        public string|null $original_access_method_id,
    ) {}
}
