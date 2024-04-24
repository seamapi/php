<?php

namespace Seam\Objects;

class Event
{
    public static function from_json(mixed $json): Event|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            action_attempt_id: $json->action_attempt_id ?? null,
            client_session_id: $json->client_session_id ?? null,
            created_at: $json->created_at,
            device_id: $json->device_id ?? null,
            enrollment_automation_id: $json->enrollment_automation_id ?? null,
            event_id: $json->event_id,
            event_type: $json->event_type,
            occurred_at: $json->occurred_at,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string|null $acs_credential_id,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $action_attempt_id,
        public string|null $client_session_id,
        public string $created_at,
        public string|null $device_id,
        public string|null $enrollment_automation_id,
        public string $event_id,
        public string $event_type,
        public string $occurred_at,
        public string $workspace_id
    ) {
    }
}
