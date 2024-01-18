<?php

namespace Seam\Objects;

class PhoneDormakabaOracodeMetadata
{
    
    public static function from_json(mixed $json): PhoneDormakabaOracodeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            door_id: $json->door_id,
            door_name: $json->door_name,
            device_id: $json->device_id ?? null,
            site_id: $json->site_id,
            site_name: $json->site_name,
            iana_timezone: $json->iana_timezone ?? null,
            predefined_time_slots: array_map(
          fn ($p) => PhonePredefinedTimeSlots::from_json($p),
          $json->predefined_time_slots ?? []
        ),
        );
    }
  

    
    public function __construct(
        public float $door_id,
        public string $door_name,
        public float | null $device_id,
        public float $site_id,
        public string $site_name,
        public string | null $iana_timezone,
        public array | null $predefined_time_slots,
    ) {
    }
  
}
