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
            code: $json->code ?? null,
            created_at: $json->created_at,
            device_id: $json->device_id,
            ends_at: $json->ends_at ?? null,
            errors: $json->errors,
            is_managed: $json->is_managed,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
            status: $json->status,
            type: $json->type,
            warnings: array_map(
                fn($w) => UnmanagedAccessCodeWarnings::from_json($w),
                $json->warnings ?? []
            )
        );
    }

    public function __construct(
        public string $access_code_id,
        public string|null $code,
        public string $created_at,
        public string $device_id,
        public string|null $ends_at,
        public array $errors,
        public bool $is_managed,
        public string|null $name,
        public string|null $starts_at,
        public string $status,
        public string $type,
        public array $warnings
    ) {
    }
}
