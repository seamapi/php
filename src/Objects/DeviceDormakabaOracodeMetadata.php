<?php

namespace Seam\Objects;

class DeviceDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json
    ): DeviceDormakabaOracodeMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_is_wireless: $json->door_is_wireless,
            door_name: $json->door_name,
            site_name: $json->site_name,
            device_id: $json->device_id ?? null,
            door_id: $json->door_id ?? null,
            iana_timezone: $json->iana_timezone ?? null,
            predefined_time_slots: array_map(
                fn($p) => DevicePredefinedTimeSlots::from_json($p),
                $json->predefined_time_slots ?? []
            ),
            site_id: $json->site_id ?? null
        );
    }

    public function __construct(
        public bool $door_is_wireless,
        public string $door_name,
        public string $site_name,
        public float|null $device_id,
        public float|null $door_id,
        public string|null $iana_timezone,
        public array|null $predefined_time_slots,
        public float|null $site_id
    ) {
    }
}
