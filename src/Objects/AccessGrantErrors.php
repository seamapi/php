<?php

namespace Seam\Objects;

class AccessGrantErrors
{
    public static function from_json(mixed $json): AccessGrantErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
            missing_device_ids: $json->missing_device_ids ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
        public array|null $missing_device_ids,
    ) {}
}
