<?php

namespace Seam\Objects;

class Phone
{
    public static function from_json(mixed $json): Phone|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            custom_metadata: $json->custom_metadata ?? null,
            device_id: $json->device_id ?? null,
            device_type: $json->device_type ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => PhoneErrors::from_json($e),
                $json->errors ?? [],
            ),
            nickname: $json->nickname ?? null,
            properties: isset($json->properties)
                ? PhoneProperties::from_json($json->properties)
                : null,
            warnings: array_map(
                fn($w) => PhoneWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public mixed $custom_metadata,
        public string|null $device_id,
        public string|null $device_type,
        public string|null $display_name,
        public array $errors,
        public string|null $nickname,
        public PhoneProperties|null $properties,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}
