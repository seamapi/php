<?php

namespace Seam\Objects;

class DeviceProvider
{
    public static function from_json(mixed $json): DeviceProvider|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_provider_name: $json->device_provider_name,
            display_name: $json->display_name,
            image_url: $json->image_url,
            provider_categories: $json->provider_categories,
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
            can_turn_off_hvac: $json->can_turn_off_hvac ?? null
        );
    }

    public function __construct(
        public string $device_provider_name,
        public string $display_name,
        public string $image_url,
        public array $provider_categories,
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
        public bool|null $can_turn_off_hvac
    ) {
    }
}
