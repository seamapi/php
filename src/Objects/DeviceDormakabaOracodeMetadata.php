<?php

namespace Seam\Objects;

class DeviceDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceDormakabaOracodeMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            door_id: $json->door_id ?? null,
            door_is_wireless: $json->door_is_wireless ?? null,
            door_name: $json->door_name ?? null,
            iana_timezone: $json->iana_timezone ?? null,
            predefined_time_slots: array_map(
                fn($p) => DevicePredefinedTimeSlots::from_json($p),
                $json->predefined_time_slots ?? [],
            ),
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
        );
    }

    public function __construct(
        public mixed $device_id,
        public float|null $door_id,
        public bool|null $door_is_wireless,
        public string|null $door_name,
        public string|null $iana_timezone,
        public array $predefined_time_slots,
        public float|null $site_id,
        public string|null $site_name,
    ) {}
}
