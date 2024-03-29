<?php

namespace Seam\Objects;

class UnmanagedDevice
{
    
    public static function from_json(mixed $json): UnmanagedDevice|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id,
            device_type: $json->device_type,
            connected_account_id: $json->connected_account_id,
            capabilities_supported: $json->capabilities_supported,
            workspace_id: $json->workspace_id,
            errors: array_map(
          fn ($e) => UnmanagedDeviceErrors::from_json($e),
          $json->errors ?? []
        ),
            warnings: array_map(
          fn ($w) => UnmanagedDeviceWarnings::from_json($w),
          $json->warnings ?? []
        ),
            created_at: $json->created_at,
            is_managed: $json->is_managed,
            properties: UnmanagedDeviceProperties::from_json($json->properties),
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_type,
        public string $connected_account_id,
        public array $capabilities_supported,
        public string $workspace_id,
        public array $errors,
        public array $warnings,
        public string $created_at,
        public bool $is_managed,
        public UnmanagedDeviceProperties $properties,
    ) {
    }
  
}
