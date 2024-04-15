<?php

namespace Seam\Objects;

class DeviceDormakabaOracodeMetadata
{
    
    public static function from_json(mixed $json): DeviceDormakabaOracodeMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            door_id: $json->door_id,
            door_is_wireless: $json->door_is_wireless,
            door_name: $json->door_name,
<<<<<<< HEAD
            device_id: $json->device_id ?? null,
            door_is_wireless: $json->door_is_wireless,
            site_id: $json->site_id,
            site_name: $json->site_name,
=======
>>>>>>> a5349eae5aa544a3669bb442227dfd28523859f1
            iana_timezone: $json->iana_timezone ?? null,
            predefined_time_slots: array_map(
          fn ($p) => DevicePredefinedTimeSlots::from_json($p),
          $json->predefined_time_slots ?? []
        ),
            site_id: $json->site_id,
            site_name: $json->site_name,
        );
    }
  

    
    public function __construct(
        public int | null $device_id,
        public int $door_id,
        public bool $door_is_wireless,
        public string $door_name,
<<<<<<< HEAD
        public int | null $device_id,
        public bool $door_is_wireless,
        public int $site_id,
        public string $site_name,
=======
>>>>>>> a5349eae5aa544a3669bb442227dfd28523859f1
        public string | null $iana_timezone,
        public array | null $predefined_time_slots,
        public int $site_id,
        public string $site_name,
    ) {
    }
  
}
