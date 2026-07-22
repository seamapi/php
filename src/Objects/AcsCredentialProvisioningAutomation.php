<?php

namespace Seam\Objects;

class AcsCredentialProvisioningAutomation
{
    public static function from_json(
        mixed $json,
    ): AcsCredentialProvisioningAutomation|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credential_provisioning_automation_id: $json->acs_credential_provisioning_automation_id ??
                null,
            created_at: $json->created_at ?? null,
            credential_manager_acs_system_id: $json->credential_manager_acs_system_id ??
                null,
            user_identity_id: $json->user_identity_id ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $acs_credential_provisioning_automation_id,
        public string|null $created_at,
        public string|null $credential_manager_acs_system_id,
        public string|null $user_identity_id,
        public string|null $workspace_id,
    ) {}
}
