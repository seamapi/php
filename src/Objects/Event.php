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
            created_at: $json->created_at,
            event_description: $json->event_description,
            event_id: $json->event_id,
            event_type: $json->event_type,
            occurred_at: $json->occurred_at,
            workspace_id: $json->workspace_id,
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            action_attempt_id: $json->action_attempt_id ?? null,
            client_session_id: $json->client_session_id ?? null,
            climate_preset_key: $json->climate_preset_key ?? null,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            device_id: $json->device_id ?? null,
            enrollment_automation_id: $json->enrollment_automation_id ?? null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            is_fallback_climate_preset: $json->is_fallback_climate_preset ??
                null,
            method: $json->method ?? null,
            thermostat_schedule_id: $json->thermostat_schedule_id ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public string $event_description,
        public string $event_id,
        public string $event_type,
        public string $occurred_at,
        public string $workspace_id,
        public string|null $acs_credential_id,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $action_attempt_id,
        public string|null $client_session_id,
        public string|null $climate_preset_key,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $device_id,
        public string|null $enrollment_automation_id,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $is_fallback_climate_preset,
        public string|null $method,
        public string|null $thermostat_schedule_id
    ) {
    }
}
