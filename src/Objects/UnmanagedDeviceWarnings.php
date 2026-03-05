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
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code,
            active_access_code_count: $json->active_access_code_count ?? null,
            max_active_access_code_count: $json->max_active_access_code_count ??
                null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code,
        public mixed $active_access_code_count,
        public mixed $max_active_access_code_count,
    ) {}
}
