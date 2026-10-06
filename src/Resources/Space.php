<?php

namespace Seam\Resources {
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
                device_count: $json->device_count ?? null,
                display_name: $json->display_name ?? null,
                name: $json->name ?? null,
                space_id: $json->space_id ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\Space\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                customer_data: isset($json->customer_data)
                    ? \Seam\Resources\Space\CustomerData::from_json(
                        $json->customer_data,
                    )
                    : null,
                customer_key: $json->customer_key ?? null,
                geolocation: isset($json->geolocation)
                    ? \Seam\Resources\Space\Geolocation::from_json(
                        $json->geolocation,
                    )
                    : null,
                space_key: $json->space_key ?? null,
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
             * Number of devices in the space.
             */
            public float|null $device_count,
            /**
             * Display name for the space.
             */
            public string|null $display_name,
            /**
             * Name of the space.
             */
            public string|null $name,
            /**
             * ID of the space.
             */
            public string|null $space_id,
            /**
             * Warnings associated with the space.
             *
             * @var list<\Seam\Resources\Space\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace associated with the space.
             */
            public string|null $workspace_id,
            /**
             * Reservation/stay-related defaults for the space. Also carries the provider/PMS-supplied name under a `<connector_type>_name` key (e.g. `guesty_name`), which Seam preserves when you rename the space (read-only — managed by Seam).
             */
            public \Seam\Resources\Space\CustomerData|null $customer_data = null,
            /**
             * Customer key associated with the space.
             */
            public string|null $customer_key = null,
            /**
             * Geographic coordinates (latitude and longitude) of the space.
             */
            public \Seam\Resources\Space\Geolocation|null $geolocation = null,
            /**
             * Unique key for the space within the workspace.
             */
            public string|null $space_key = null,
        ) {}
    }
}

namespace Seam\Resources\Space {
    /**
     * Reservation/stay-related defaults for the space. Also carries the provider/PMS-supplied name under a `<connector_type>_name` key (e.g. `guesty_name`), which Seam preserves when you rename the space (read-only — managed by Seam).
     */
    class CustomerData
    {
        public static function from_json(mixed $json): CustomerData|null
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
            public string|null $address = null,
            /**
             * Default check-in time for reservations at the space, as HH:mm or HH:mm:ss.
             */
            public string|null $default_checkin_time = null,
            /**
             * Default check-out time for reservations at the space, as HH:mm or HH:mm:ss.
             */
            public string|null $default_checkout_time = null,
            /**
             * IANA time zone for the space, e.g. America/Los_Angeles.
             */
            public string|null $time_zone = null,
        ) {}
    }

    /**
     * Geographic coordinates (latitude and longitude) of the space.
     */
    class Geolocation
    {
        public static function from_json(mixed $json): Geolocation|null
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

    /**
     * Warnings associated with the space. Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\Space\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\Space\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\Space\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    warning_code: $json->warning_code ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\Space\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Space\Warnings {
    /**
     * Indicates that the space is being deleted. Seam removes it, revokes its access grants, and detaches its devices and entrances shortly.
     */
    final class BeingDeleted extends \Seam\Resources\Space\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\Space\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    enum WarningCode: string
    {
        case BEING_DELETED = "being_deleted";
    }
}
