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
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            original_access_method_id: $json->original_access_method_id ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public string|null $original_access_method_id,
        public string|null $warning_code,
    ) {}
}
