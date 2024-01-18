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
            type: $json->type,
            access_code_id: $json->access_code_id,
            device_id: $json->device_id,
            name: $json->name ?? null,
            code: $json->code ?? null,
            created_at: $json->created_at,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            is_managed: $json->is_managed,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            status: $json->status,
        );
    }
  

    
    public function __construct(
        public string $type,
        public string $access_code_id,
        public string $device_id,
        public string | null $name,
        public string | null $code,
        public string $created_at,
        public mixed $errors,
        public mixed $warnings,
        public bool $is_managed,
        public string | null $starts_at,
        public string | null $ends_at,
        public string $status,
    ) {
    }
  
}
