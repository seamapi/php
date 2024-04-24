<?php

namespace Seam\Objects;

class AcsCredentialPool
{
    public static function from_json(mixed $json): AcsCredentialPool|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credential_pool_id: $json->acs_credential_pool_id,
            acs_system_id: $json->acs_system_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            external_type: $json->external_type,
            external_type_display_name: $json->external_type_display_name,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $acs_credential_pool_id,
        public string $acs_system_id,
        public string $created_at,
        public string $display_name,
        public string $external_type,
        public string $external_type_display_name,
        public string $workspace_id
    ) {
    }
}
