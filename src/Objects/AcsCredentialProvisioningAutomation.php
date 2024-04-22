<?php

namespace Seam\Objects;

class AcsCredentialProvisioningAutomation
{
    public static function from_json(
        mixed $json
    ): AcsCredentialProvisioningAutomation|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credential_provisioning_automation_id: $json->acs_credential_provisioning_automation_id,
            created_at: $json->created_at,
            credential_manager_acs_system_id: $json->credential_manager_acs_system_id,
            user_identity_id: $json->user_identity_id,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $acs_credential_provisioning_automation_id,
        public string $created_at,
        public string $credential_manager_acs_system_id,
        public string $user_identity_id,
        public string $workspace_id
    ) {
    }
}
