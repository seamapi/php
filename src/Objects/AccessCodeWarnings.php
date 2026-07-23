<?php

namespace Seam\Objects;

class AccessCodeWarnings
{
    public static function from_json(mixed $json): AccessCodeWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            change_type: $json->change_type ?? null,
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            modified_fields: array_map(
                fn($m) => AccessCodeModifiedFields::from_json($m),
                $json->modified_fields ?? [],
            ),
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $change_type,
        public string|null $created_at,
        public string|null $message,
        public array $modified_fields,
        public string|null $warning_code,
    ) {}
}
