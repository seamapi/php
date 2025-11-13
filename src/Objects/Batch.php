<?php

namespace Seam\Objects;

class Batch
{
    public static function from_json(mixed $json): Batch|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_codes: $json->access_codes ?? null,
            access_grants: $json->access_grants ?? null,
            access_methods: $json->access_methods ?? null,
            acs_access_groups: $json->acs_access_groups ?? null,
            acs_credentials: $json->acs_credentials ?? null,
            acs_encoders: $json->acs_encoders ?? null,
            acs_entrances: $json->acs_entrances ?? null,
            acs_systems: $json->acs_systems ?? null,
            acs_users: $json->acs_users ?? null,
            action_attempts: $json->action_attempts ?? null,
            client_sessions: $json->client_sessions ?? null,
            connect_webviews: $json->connect_webviews ?? null,
            connected_accounts: $json->connected_accounts ?? null,
            customization_profiles: $json->customization_profiles ?? null,
            devices: $json->devices ?? null,
            events: $json->events ?? null,
            instant_keys: $json->instant_keys ?? null,
            noise_thresholds: $json->noise_thresholds ?? null,
            spaces: $json->spaces ?? null,
            thermostat_daily_programs: $json->thermostat_daily_programs ?? null,
            thermostat_schedules: $json->thermostat_schedules ?? null,
            unmanaged_access_codes: $json->unmanaged_access_codes ?? null,
            unmanaged_acs_access_groups: $json->unmanaged_acs_access_groups ??
                null,
            unmanaged_acs_credentials: $json->unmanaged_acs_credentials ?? null,
            unmanaged_acs_users: $json->unmanaged_acs_users ?? null,
            unmanaged_devices: $json->unmanaged_devices ?? null,
            user_identities: $json->user_identities ?? null,
            workspaces: $json->workspaces ?? null,
        );
    }

    public function __construct(
        public array|null $access_codes,
        public array|null $access_grants,
        public array|null $access_methods,
        public array|null $acs_access_groups,
        public array|null $acs_credentials,
        public array|null $acs_encoders,
        public array|null $acs_entrances,
        public array|null $acs_systems,
        public array|null $acs_users,
        public array|null $action_attempts,
        public array|null $client_sessions,
        public array|null $connect_webviews,
        public array|null $connected_accounts,
        public array|null $customization_profiles,
        public array|null $devices,
        public array|null $events,
        public array|null $instant_keys,
        public array|null $noise_thresholds,
        public array|null $spaces,
        public array|null $thermostat_daily_programs,
        public array|null $thermostat_schedules,
        public array|null $unmanaged_access_codes,
        public array|null $unmanaged_acs_access_groups,
        public array|null $unmanaged_acs_credentials,
        public array|null $unmanaged_acs_users,
        public array|null $unmanaged_devices,
        public array|null $user_identities,
        public array|null $workspaces,
    ) {}
}
