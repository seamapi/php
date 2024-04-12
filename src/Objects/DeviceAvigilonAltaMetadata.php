<?php

namespace Seam\Objects;

class DeviceAvigilonAltaMetadata
{
    
    public static function from_json(mixed $json): DeviceAvigilonAltaMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            entry_name: $json->entry_name,
            org_name: $json->org_name,
            site_id: $json->site_id,
            site_name: $json->site_name,
            zone_id: $json->zone_id,
            zone_name: $json->zone_name,
        );
    }
  

    
    public function __construct(
        public string $entry_name,
        public string $org_name,
        public int $site_id,
        public string $site_name,
        public int $zone_id,
        public string $zone_name,
    ) {
    }
  
}
