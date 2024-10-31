<?php

namespace Seam\Objects;

class UnmanagedAccessCode
{
    public static function from_json(mixed $json): UnmanagedAccessCode|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_code_id: $json->access_code_id,
            created_at: $json->created_at,
            device_id: $json->device_id,
            errors: array_map(
                fn($e) => UnmanagedAccessCodeErrors::from_json($e),
                $json->errors ?? []
            ),
            is_managed: $json->is_managed,
            status: $json->status,
            type: $json->type,
            warnings: array_map(
                fn($w) => UnmanagedAccessCodeWarnings::from_json($w),
                $json->warnings ?? []
            ),
            code: $json->code ?? null,
            name: $json->name ?? null,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null
        );
    }

    public function __construct(
        public string $access_code_id,
        public string $created_at,
        public string $device_id,
        public array $errors,
        public bool $is_managed,
        public string $status,
        public string $type,
        public array $warnings,
        public string|null $code,
        public string|null $name,
        public string|null $ends_at,
        public string|null $starts_at
    ) {
    }
}
