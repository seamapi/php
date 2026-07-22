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
            created_at: $json->created_at ?? null,
            credential_manager_acs_system_id: $json->credential_manager_acs_system_id ??
                null,
            enrollment_automation_id: $json->enrollment_automation_id ?? null,
            user_identity_id: $json->user_identity_id ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $credential_manager_acs_system_id,
        public string|null $enrollment_automation_id,
        public string|null $user_identity_id,
        public string|null $workspace_id,
    ) {}
}
