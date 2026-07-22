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
        public mixed $access_codes,
        public mixed $access_grants,
        public mixed $access_methods,
        public mixed $acs_access_groups,
        public mixed $acs_credentials,
        public mixed $acs_encoders,
        public mixed $acs_entrances,
        public mixed $acs_systems,
        public mixed $acs_users,
        public mixed $action_attempts,
        public mixed $client_sessions,
        public mixed $connect_webviews,
        public mixed $connected_accounts,
        public mixed $customization_profiles,
        public mixed $devices,
        public mixed $events,
        public mixed $instant_keys,
        public mixed $noise_thresholds,
        public mixed $spaces,
        public mixed $thermostat_daily_programs,
        public mixed $thermostat_schedules,
        public mixed $unmanaged_access_codes,
        public mixed $unmanaged_acs_access_groups,
        public mixed $unmanaged_acs_credentials,
        public mixed $unmanaged_acs_users,
        public mixed $unmanaged_devices,
        public mixed $user_identities,
        public mixed $workspaces,
    ) {}
}
