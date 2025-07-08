<?php

namespace Seam\Objects;

class Location
{
    public static function from_json(mixed $json): Location|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            display_name: $json->display_name,
            location_id: $json->location_id,
            name: $json->name,
            workspace_id: $json->workspace_id,
            geolocation: isset($json->geolocation)
                ? LocationGeolocation::from_json($json->geolocation)
                : null,
            time_zone: $json->time_zone ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public string $display_name,
        public string $location_id,
        public string $name,
        public string $workspace_id,
        public LocationGeolocation|null $geolocation,
        public string|null $time_zone
    ) {}
}
