<?php

namespace Seam\Resources;

class Space
{
    public static function from_json(mixed $json): Space|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_count: $json->acs_entrance_count ?? null,
            created_at: $json->created_at ?? null,
            customer_data: isset($json->customer_data)
                ? SpaceCustomerData::from_json($json->customer_data)
                : null,
            customer_key: $json->customer_key ?? null,
            device_count: $json->device_count ?? null,
            display_name: $json->display_name ?? null,
            geolocation: isset($json->geolocation)
                ? SpaceGeolocation::from_json($json->geolocation)
                : null,
            name: $json->name ?? null,
            space_id: $json->space_id ?? null,
            space_key: $json->space_key ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public float|null $acs_entrance_count,
        public string|null $created_at,
        public SpaceCustomerData|null $customer_data,
        public string|null $customer_key,
        public float|null $device_count,
        public string|null $display_name,
        public SpaceGeolocation|null $geolocation,
        public string|null $name,
        public string|null $space_id,
        public string|null $space_key,
        public string|null $workspace_id,
    ) {}
}

class SpaceCustomerData
{
    public static function from_json(mixed $json): SpaceCustomerData|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            address: $json->address ?? null,
            default_checkin_time: $json->default_checkin_time ?? null,
            default_checkout_time: $json->default_checkout_time ?? null,
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string|null $address,
        public string|null $default_checkin_time,
        public string|null $default_checkout_time,
        public string|null $time_zone,
    ) {}
}

class SpaceGeolocation
{
    public static function from_json(mixed $json): SpaceGeolocation|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            latitude: $json->latitude ?? null,
            longitude: $json->longitude ?? null,
        );
    }

    public function __construct(
        public float|null $latitude,
        public float|null $longitude,
    ) {}
}
