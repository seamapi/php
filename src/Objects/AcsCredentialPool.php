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
            acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $acs_credential_pool_id,
        public string|null $acs_system_id,
        public string|null $created_at,
        public string|null $display_name,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $workspace_id,
    ) {}
}
