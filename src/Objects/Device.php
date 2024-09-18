<?php

namespace Seam\Objects;

class Device
{
    public static function from_json(mixed $json): Device|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            can_hvac_cool: $json->can_hvac_cool ?? null,
            can_hvac_heat: $json->can_hvac_heat ?? null,
            can_hvac_heat_cool: $json->can_hvac_heat_cool ?? null,
            can_program_offline_access_codes: $json->can_program_offline_access_codes ??
                null,
            can_program_online_access_codes: $json->can_program_online_access_codes ??
                null,
            can_remotely_lock: $json->can_remotely_lock ?? null,
            can_remotely_unlock: $json->can_remotely_unlock ?? null,
            can_simulate_connection: $json->can_simulate_connection ?? null,
            can_simulate_disconnection: $json->can_simulate_disconnection ??
                null,
            can_simulate_removal: $json->can_simulate_removal ?? null,
            can_turn_off_hvac: $json->can_turn_off_hvac ?? null,
            capabilities_supported: $json->capabilities_supported,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            custom_metadata: $json->custom_metadata,
            device_id: $json->device_id,
            device_type: $json->device_type,
            display_name: $json->display_name,
            errors: $json->errors,
            is_managed: $json->is_managed,
            location: isset($json->location)
                ? DeviceLocation::from_json($json->location)
                : null,
            nickname: $json->nickname ?? null,
            properties: DeviceProperties::from_json($json->properties),
            warnings: array_map(
                fn($w) => DeviceWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public bool|null $can_hvac_cool,
        public bool|null $can_hvac_heat,
        public bool|null $can_hvac_heat_cool,
        public bool|null $can_program_offline_access_codes,
        public bool|null $can_program_online_access_codes,
        public bool|null $can_remotely_lock,
        public bool|null $can_remotely_unlock,
        public bool|null $can_simulate_connection,
        public bool|null $can_simulate_disconnection,
        public bool|null $can_simulate_removal,
        public bool|null $can_turn_off_hvac,
        public array $capabilities_supported,
        public string $connected_account_id,
        public string $created_at,
        public mixed $custom_metadata,
        public string $device_id,
        public string $device_type,
        public string $display_name,
        public array $errors,
        public bool $is_managed,
        public DeviceLocation|null $location,
        public string|null $nickname,
        public DeviceProperties $properties,
        public array $warnings,
        public string $workspace_id
    ) {
    }
}
