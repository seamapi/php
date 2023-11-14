<?php

namespace Seam\Objects;

class Device
{
    public static function from_json(mixed $json): Device|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            device_type: $json->device_type ?? null,
            capabilities_supported: $json->capabilities_supported ?? null,
            properties: $json->properties ?? null,
            location: $json->location ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            workspace_id: $json->workspace_id ?? null,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            created_at: $json->created_at ?? null,
            is_managed: $json->is_managed ?? null
        );
    }

    public function __construct(
        public string|null $device_id,
        public mixed $device_type,
        public array|null $capabilities_supported,
        public mixed $properties,
        public mixed $location,
        public string|null $connected_account_id,
        public string|null $workspace_id,
        public array|null $errors,
        public array|null $warnings,
        public string|null $created_at,
        public bool|null $is_managed
    ) {
    }
}
