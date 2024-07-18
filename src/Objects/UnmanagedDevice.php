<?php

namespace Seam\Objects;

class UnmanagedDevice
{
    public static function from_json(mixed $json): UnmanagedDevice|null
    {
        if (!$json) {
            return null;
        }
        return new self(
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
            capabilities_supported: $json->capabilities_supported,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            device_id: $json->device_id,
            device_type: $json->device_type,
            errors: $json->errors,
            is_managed: $json->is_managed,
            location: isset($json->location)
                ? UnmanagedDeviceLocation::from_json($json->location)
                : null,
            properties: UnmanagedDeviceProperties::from_json($json->properties),
            warnings: array_map(
                fn($w) => UnmanagedDeviceWarnings::from_json($w),
                $json->warnings ?? []
            ),
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public bool|null $can_program_offline_access_codes,
        public bool|null $can_program_online_access_codes,
        public bool|null $can_remotely_lock,
        public bool|null $can_remotely_unlock,
        public bool|null $can_simulate_connection,
        public bool|null $can_simulate_disconnection,
        public bool|null $can_simulate_removal,
        public array $capabilities_supported,
        public string $connected_account_id,
        public string $created_at,
        public string $device_id,
        public string $device_type,
        public array $errors,
        public bool $is_managed,
        public UnmanagedDeviceLocation|null $location,
        public UnmanagedDeviceProperties $properties,
        public array $warnings,
        public string $workspace_id
    ) {
    }
}
