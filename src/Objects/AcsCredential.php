<?php

namespace Seam\Objects;

class AcsCredential
{
    
    public static function from_json(mixed $json): AcsCredential|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credential_id: $json->acs_credential_id,
            acs_user_id: $json->acs_user_id ?? null,
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_system_id: $json->acs_system_id,
            parent_acs_credential_id: $json->parent_acs_credential_id ?? null,
            display_name: $json->display_name,
            code: $json->code ?? null,
            access_method: $json->access_method,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ?? null,
            created_at: $json->created_at,
            workspace_id: $json->workspace_id,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->ends_at ?? null,
            errors: array_map(
          fn ($e) => AcsCredentialErrors::from_json($e),
          $json->errors ?? []
        ),
            warnings: array_map(
          fn ($w) => AcsCredentialWarnings::from_json($w),
          $json->warnings ?? []
        ),
            is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ?? null,
            visionline_metadata: isset($json->visionline_metadata) ? AcsCredentialVisionlineMetadata::from_json($json->visionline_metadata) : null,
        );
    }
  

    
    public function __construct(
        public string $acs_credential_id,
        public string | null $acs_user_id,
        public string | null $acs_credential_pool_id,
        public string $acs_system_id,
        public string | null $parent_acs_credential_id,
        public string $display_name,
        public string | null $code,
        public string $access_method,
        public string | null $external_type,
        public string | null $external_type_display_name,
        public string $created_at,
        public string $workspace_id,
        public string | null $starts_at,
        public string | null $ends_at,
        public array $errors,
        public array $warnings,
        public bool | null $is_multi_phone_sync_credential,
        public AcsCredentialVisionlineMetadata | null $visionline_metadata,
    ) {
    }
  
}
