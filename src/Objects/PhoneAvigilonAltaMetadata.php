<?php

namespace Seam\Objects;

class PhoneAvigilonAltaMetadata
{
    
    public static function from_json(mixed $json): PhoneAvigilonAltaMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            entry_name: $json->entry_name,
            org_name: $json->org_name,
            zone_id: $json->zone_id,
            zone_name: $json->zone_name,
            site_id: $json->site_id,
            site_name: $json->site_name,
        );
    }
  

    
    public function __construct(
        public string $entry_name,
        public string $org_name,
        public float $zone_id,
        public string $zone_name,
        public float $site_id,
        public string $site_name,
    ) {
    }
  
}
