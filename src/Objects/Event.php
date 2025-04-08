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
            access_code_id: $json->access_code_id ?? null,
            acs_access_group_id: $json->acs_access_group_id ?? null,
            acs_credential_id: $json->acs_credential_id ?? null,
            acs_encoder_id: $json->acs_encoder_id ?? null,
            acs_entrance_id: $json->acs_entrance_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            action_attempt_id: $json->action_attempt_id ?? null,
            action_type: $json->action_type ?? null,
            backup_access_code_id: $json->backup_access_code_id ?? null,
            battery_level: $json->battery_level ?? null,
            battery_status: $json->battery_status ?? null,
            client_session_id: $json->client_session_id ?? null,
            climate_preset_key: $json->climate_preset_key ?? null,
            connect_webview_id: $json->connect_webview_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            cooling_set_point_celsius: $json->cooling_set_point_celsius ?? null,
            cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                null,
            created_at: $json->created_at ?? null,
            desired_temperature_celsius: $json->desired_temperature_celsius ??
                null,
            desired_temperature_fahrenheit: $json->desired_temperature_fahrenheit ??
                null,
            device_id: $json->device_id ?? null,
            device_name: $json->device_name ?? null,
            enrollment_automation_id: $json->enrollment_automation_id ?? null,
            error_code: $json->error_code ?? null,
            event_id: $json->event_id ?? null,
            event_type: $json->event_type ?? null,
            fan_mode_setting: $json->fan_mode_setting ?? null,
            heating_set_point_celsius: $json->heating_set_point_celsius ?? null,
            heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                null,
            hvac_mode_setting: $json->hvac_mode_setting ?? null,
            is_fallback_climate_preset: $json->is_fallback_climate_preset ??
                null,
            method: $json->method ?? null,
            minut_metadata: $json->minut_metadata ?? null,
            noise_level_decibels: $json->noise_level_decibels ?? null,
            noise_level_nrs: $json->noise_level_nrs ?? null,
            noise_threshold_id: $json->noise_threshold_id ?? null,
            noise_threshold_name: $json->noise_threshold_name ?? null,
            noiseaware_metadata: $json->noiseaware_metadata ?? null,
            occurred_at: $json->occurred_at ?? null,
            status: $json->status ?? null,
            temperature_celsius: $json->temperature_celsius ?? null,
            temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
            workspace_id: $json->workspace_id ?? null,
            code: $json->code ?? null,
            lower_limit_celsius: $json->lower_limit_celsius ?? null,
            lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
            thermostat_schedule_id: $json->thermostat_schedule_id ?? null,
            upper_limit_celsius: $json->upper_limit_celsius ?? null,
            upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null
        );
    }

    public function __construct(
        public string|null $access_code_id,
        public string|null $acs_access_group_id,
        public string|null $acs_credential_id,
        public string|null $acs_encoder_id,
        public string|null $acs_entrance_id,
        public string|null $acs_system_id,
        public string|null $acs_user_id,
        public string|null $action_attempt_id,
        public string|null $action_type,
        public string|null $backup_access_code_id,
        public float|null $battery_level,
        public string|null $battery_status,
        public string|null $client_session_id,
        public string|null $climate_preset_key,
        public string|null $connect_webview_id,
        public string|null $connected_account_id,
        public float|null $cooling_set_point_celsius,
        public float|null $cooling_set_point_fahrenheit,
        public string|null $created_at,
        public float|null $desired_temperature_celsius,
        public float|null $desired_temperature_fahrenheit,
        public string|null $device_id,
        public string|null $device_name,
        public string|null $enrollment_automation_id,
        public string|null $error_code,
        public string|null $event_id,
        public string|null $event_type,
        public string|null $fan_mode_setting,
        public float|null $heating_set_point_celsius,
        public float|null $heating_set_point_fahrenheit,
        public string|null $hvac_mode_setting,
        public bool|null $is_fallback_climate_preset,
        public string|null $method,
        public mixed $minut_metadata,
        public float|null $noise_level_decibels,
        public float|null $noise_level_nrs,
        public string|null $noise_threshold_id,
        public string|null $noise_threshold_name,
        public mixed $noiseaware_metadata,
        public string|null $occurred_at,
        public string|null $status,
        public float|null $temperature_celsius,
        public float|null $temperature_fahrenheit,
        public string|null $workspace_id,
        public string|null $code,
        public float|null $lower_limit_celsius,
        public float|null $lower_limit_fahrenheit,
        public string|null $thermostat_schedule_id,
        public float|null $upper_limit_celsius,
        public float|null $upper_limit_fahrenheit
    ) {
    }
}
