<?php

namespace Seam\Objects;

class EnrollmentAutomation
{
    public static function from_json(mixed $json): EnrollmentAutomation|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            credential_manager_acs_system_id: $json->credential_manager_acs_system_id,
            enrollment_automation_id: $json->enrollment_automation_id,
            user_identity_id: $json->user_identity_id,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $created_at,
        public string $credential_manager_acs_system_id,
        public string $enrollment_automation_id,
        public string $user_identity_id,
        public string $workspace_id
    ) {
    }
}
