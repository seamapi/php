<?php

namespace Seam\Objects;

class UnmanagedDeviceWarnings
{
    public static function from_json(mixed $json): UnmanagedDeviceWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            active_access_code_count: $json->active_access_code_count ?? null,
            created_at: $json->created_at ?? null,
            max_active_access_code_count: $json->max_active_access_code_count ??
                null,
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public float|null $active_access_code_count,
        public string|null $created_at,
        public float|null $max_active_access_code_count,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
