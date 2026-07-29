<?php

namespace Seam\Resources;

/**
 * Represents a space that is a logical grouping of devices and entrances. You can assign access to an entire space, thereby making granting access more efficient.
 */
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
        /**
         * Number of entrances in the space.
         */
        public float|null $acs_entrance_count,
        /**
         * Date and time at which the space was created.
         */
        public string|null $created_at,
        /**
         * Reservation/stay-related defaults for the space. Also carries the provider/PMS-supplied name under a `<connector_type>_name` key (e.g. `guesty_name`), which Seam preserves when you rename the space (read-only — managed by Seam).
         */
        public SpaceCustomerData|null $customer_data,
        /**
         * Customer key associated with the space.
         */
        public string|null $customer_key,
        /**
         * Number of devices in the space.
         */
        public float|null $device_count,
        /**
         * Display name for the space.
         */
        public string|null $display_name,
        /**
         * Geographic coordinates (latitude and longitude) of the space.
         */
        public SpaceGeolocation|null $geolocation,
        /**
         * Name of the space.
         */
        public string|null $name,
        /**
         * ID of the space.
         */
        public string|null $space_id,
        /**
         * Unique key for the space within the workspace.
         */
        public string|null $space_key,
        /**
         * ID of the workspace associated with the space.
         */
        public string|null $workspace_id,
    ) {}
}

/**
 * Reservation/stay-related defaults for the space. Also carries the provider/PMS-supplied name under a `<connector_type>_name` key (e.g. `guesty_name`), which Seam preserves when you rename the space (read-only — managed by Seam).
 */
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
        /**
         * Postal address for the space.
         */
        public string|null $address,
        /**
         * Default check-in time for reservations at the space, as HH:mm or HH:mm:ss.
         */
        public string|null $default_checkin_time,
        /**
         * Default check-out time for reservations at the space, as HH:mm or HH:mm:ss.
         */
        public string|null $default_checkout_time,
        /**
         * IANA time zone for the space, e.g. America/Los_Angeles.
         */
        public string|null $time_zone,
    ) {}
}

/**
 * Geographic coordinates (latitude and longitude) of the space.
 */
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
        /**
         * Latitude of the space, in decimal degrees.
         */
        public float|null $latitude,
        /**
         * Longitude of the space, in decimal degrees.
         */
        public float|null $longitude,
    ) {}
}
