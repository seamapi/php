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
            device_id: $json->device_id,
            device_type: $json->device_type,
            capabilities_supported: $json->capabilities_supported,
            properties: PhoneProperties::from_json($json->properties),
            location: isset($json->location) ? PhoneLocation::from_json($json->location) : null,
            workspace_id: $json->workspace_id,
            errors: array_map(
          fn ($e) => PhoneErrors::from_json($e),
          $json->errors ?? []
        ),
            warnings: array_map(
          fn ($w) => PhoneWarnings::from_json($w),
          $json->warnings ?? []
        ),
            created_at: $json->created_at,
            is_managed: $json->is_managed,
            custom_metadata: $json->custom_metadata ?? null,
            assa_abloy_credential_service_metadata: isset($json->assa_abloy_credential_service_metadata) ? PhoneAssaAbloyCredentialServiceMetadata::from_json($json->assa_abloy_credential_service_metadata) : null,
        );
    }
  

    
    public function __construct(
        public string $device_id,
        public string $device_type,
        public array $capabilities_supported,
        public PhoneProperties $properties,
        public PhoneLocation | null $location,
        public string $workspace_id,
        public array $errors,
        public array $warnings,
        public string $created_at,
        public bool $is_managed,
        public mixed $custom_metadata,
        public PhoneAssaAbloyCredentialServiceMetadata | null $assa_abloy_credential_service_metadata,
    ) {
    }
  
}
