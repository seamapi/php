<?php

namespace Seam\Objects;

class UnmanagedAccessCodeWarnings
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAccessCodeWarnings|null {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message,
            warning_code: $json->warning_code,
            change_type: $json->change_type ?? null,
            created_at: $json->created_at ?? null,
            modified_fields: array_map(
                fn($m) => UnmanagedAccessCodeModifiedFields::from_json($m),
                $json->modified_fields ?? [],
            ),
        );
    }

    public function __construct(
        public string $message,
        public string $warning_code,
        public string|null $change_type,
        public string|null $created_at,
        public array|null $modified_fields,
    ) {}
}
