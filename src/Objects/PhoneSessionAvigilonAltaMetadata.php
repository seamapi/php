<?php

namespace Seam\Objects;

class PhoneSessionAvigilonAltaMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionAvigilonAltaMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            entry_name: $json->entry_name ?? null,
            entry_relays_total_count: $json->entry_relays_total_count ?? null,
            org_name: $json->org_name ?? null,
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
            zone_id: $json->zone_id ?? null,
            zone_name: $json->zone_name ?? null,
        );
    }

    public function __construct(
        public string|null $entry_name,
        public float|null $entry_relays_total_count,
        public string|null $org_name,
        public float|null $site_id,
        public string|null $site_name,
        public float|null $zone_id,
        public string|null $zone_name,
    ) {}
}
