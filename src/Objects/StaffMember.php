<?php

namespace Seam\Objects;

class StaffMember
{
    public static function from_json(mixed $json): StaffMember|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            name: $json->name,
            staff_member_key: $json->staff_member_key,
            building_keys: $json->building_keys ?? null,
            common_area_keys: $json->common_area_keys ?? null,
            email_address: $json->email_address ?? null,
            facility_keys: $json->facility_keys ?? null,
            listing_keys: $json->listing_keys ?? null,
            phone_number: $json->phone_number ?? null,
            property_keys: $json->property_keys ?? null,
            property_listing_keys: $json->property_listing_keys ?? null,
            room_keys: $json->room_keys ?? null,
            site_keys: $json->site_keys ?? null,
            space_keys: $json->space_keys ?? null,
            unit_keys: $json->unit_keys ?? null,
        );
    }

    public function __construct(
        public string $name,
        public string $staff_member_key,
        public array|null $building_keys,
        public array|null $common_area_keys,
        public string|null $email_address,
        public array|null $facility_keys,
        public array|null $listing_keys,
        public string|null $phone_number,
        public array|null $property_keys,
        public array|null $property_listing_keys,
        public array|null $room_keys,
        public array|null $site_keys,
        public array|null $space_keys,
        public array|null $unit_keys,
    ) {}
}
