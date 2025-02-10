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
            created_at: $json->created_at,
            custom_metadata: $json->custom_metadata,
            device_id: $json->device_id,
            device_type: $json->device_type,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => PhoneErrors::from_json($e),
                $json->errors ?? []
            ),
            properties: PhoneProperties::from_json($json->properties),
            warnings: array_map(
                fn($w) => PhoneWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id,
            nickname: $json->nickname ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public mixed $custom_metadata,
        public string $device_id,
        public string $device_type,
        public string $display_name,
        public array $errors,
        public PhoneProperties $properties,
        public array $warnings,
        public string $workspace_id,
        public string|null $nickname
    ) {
    }
}
