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
            type: $json->type ?? null,
            access_code_id: $json->access_code_id ?? null,
            device_id: $json->device_id ?? null,
            name: $json->name ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at ?? null,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            is_managed: $json->is_managed ?? null,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            status: $json->status ?? null
        );
    }

    public function __construct(
        public string|null $type,
        public string|null $access_code_id,
        public string|null $device_id,
        public string|null $name,
        public string|null $code,
        public string|null $created_at,
        public mixed $errors,
        public mixed $warnings,
        public bool|null $is_managed,
        public string|null $starts_at,
        public string|null $ends_at,
        public string|null $status
    ) {
    }
}
