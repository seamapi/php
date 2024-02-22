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
            device_id: $json->device_id,
            device_type: $json->device_type,
            capabilities_supported: $json->capabilities_supported,
            properties: DeviceProperties::from_json($json->properties),
            location: isset($json->location) ? DeviceLocation::from_json($json->location) : null,
            connected_account_id: $json->connected_account_id,
            workspace_id: $json->workspace_id,
            errors: array_map(
          fn ($e) => DeviceErrors::from_json($e),
          $json->errors ?? []
        ),
            warnings: array_map(
          fn ($w) => DeviceWarnings::from_json($w),
          $json->warnings ?? []
        ),
            created_at: $json->created_at,
            is_managed: $json->is_managed,
            custom_metadata: $json->custom_metadata ?? null,
            can_remotely_unlock: $json->can_remotely_unlock ?? null,
            can_program_online_access_codes: $json->can_program_online_access_codes ?? null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_type,
        public array $capabilities_supported,
        public DeviceProperties $properties,
        public DeviceLocation | null $location,
        public string $connected_account_id,
        public string $workspace_id,
        public array $errors,
        public array $warnings,
        public string $created_at,
        public bool $is_managed,
        public mixed $custom_metadata,
        public bool | null $can_remotely_unlock,
        public bool | null $can_program_online_access_codes,
    ) {
    }
  
}
