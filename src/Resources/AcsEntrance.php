<?php

namespace Seam\Resources {
    /**
     * Represents an [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) within an [access control system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * In an access control system, an entrance is a secured door, gate, zone, or other method of entry. You can list details for all the `acs_entrance` resources in your workspace or get these details for a specific `acs_entrance`. You can also list all entrances associated with a specific credential, and you can list all credentials associated with a specific entrance.
     */
    class AcsEntrance
    {
        public static function from_json(mixed $json): AcsEntrance|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_id: $json->acs_entrance_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? AcsEntrance\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? AcsEntrance\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                avigilon_alta_metadata: isset($json->avigilon_alta_metadata)
                    ? AcsEntrance\AvigilonAltaMetadata::from_json(
                        $json->avigilon_alta_metadata,
                    )
                    : null,
                brivo_metadata: isset($json->brivo_metadata)
                    ? AcsEntrance\BrivoMetadata::from_json(
                        $json->brivo_metadata,
                    )
                    : null,
                can_belong_to_reservation: $json->can_belong_to_reservation ??
                    null,
                can_unlock_with_card: $json->can_unlock_with_card ?? null,
                can_unlock_with_cloud_key: $json->can_unlock_with_cloud_key ??
                    null,
                can_unlock_with_code: $json->can_unlock_with_code ?? null,
                can_unlock_with_mobile_key: $json->can_unlock_with_mobile_key ??
                    null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                dormakaba_ambiance_metadata: isset(
                    $json->dormakaba_ambiance_metadata,
                )
                    ? AcsEntrance\DormakabaAmbianceMetadata::from_json(
                        $json->dormakaba_ambiance_metadata,
                    )
                    : null,
                dormakaba_community_metadata: isset(
                    $json->dormakaba_community_metadata,
                )
                    ? AcsEntrance\DormakabaCommunityMetadata::from_json(
                        $json->dormakaba_community_metadata,
                    )
                    : null,
                errors: array_map(
                    fn($e) => AcsEntrance\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                hotek_metadata: isset($json->hotek_metadata)
                    ? AcsEntrance\HotekMetadata::from_json(
                        $json->hotek_metadata,
                    )
                    : null,
                is_locked: $json->is_locked ?? null,
                latch_metadata: isset($json->latch_metadata)
                    ? AcsEntrance\LatchMetadata::from_json(
                        $json->latch_metadata,
                    )
                    : null,
                salto_ks_metadata: isset($json->salto_ks_metadata)
                    ? AcsEntrance\SaltoKsMetadata::from_json(
                        $json->salto_ks_metadata,
                    )
                    : null,
                salto_space_metadata: isset($json->salto_space_metadata)
                    ? AcsEntrance\SaltoSpaceMetadata::from_json(
                        $json->salto_space_metadata,
                    )
                    : null,
                space_ids: $json->space_ids ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? AcsEntrance\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
                warnings: array_map(
                    fn($w) => AcsEntrance\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * ID of the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $acs_entrance_id,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $acs_system_id,
            /**
             * Akiles-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\AkilesMetadata|null $akiles_metadata = null,
            /**
             * ASSA ABLOY Vostio-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Avigilon Alta-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\AvigilonAltaMetadata|null $avigilon_alta_metadata = null,
            /**
             * Brivo-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\BrivoMetadata|null $brivo_metadata = null,
            /**
             * Indicates whether the ACS entrance can belong to a reservation via an access_grant.reservation_key.
             */
            public bool|null $can_belong_to_reservation = null,
            /**
             * Indicates whether the ACS entrance can be unlocked with card credentials.
             */
            public bool|null $can_unlock_with_card = null,
            /**
             * Indicates whether the ACS entrance can be unlocked with cloud key credentials.
             */
            public bool|null $can_unlock_with_cloud_key = null,
            /**
             * Indicates whether the ACS entrance can be unlocked with pin codes.
             */
            public bool|null $can_unlock_with_code = null,
            /**
             * Indicates whether the ACS entrance can be unlocked with mobile key credentials.
             */
            public bool|null $can_unlock_with_mobile_key = null,
            /**
             * ID of the [connected account](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) was created.
             */
            public string|null $created_at,
            /**
             * Display name for the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $display_name,
            /**
             * dormakaba Ambiance-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\DormakabaAmbianceMetadata|null $dormakaba_ambiance_metadata = null,
            /**
             * dormakaba Community-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\DormakabaCommunityMetadata|null $dormakaba_community_metadata = null,
            /**
             * Errors associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public array $errors,
            /**
             * Hotek-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\HotekMetadata|null $hotek_metadata = null,
            /**
             * Indicates whether the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) is currently locked.
             */
            public bool|null $is_locked = null,
            /**
             * Latch-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\LatchMetadata|null $latch_metadata = null,
            /**
             * Salto KS-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\SaltoKsMetadata|null $salto_ks_metadata = null,
            /**
             * Salto Space-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\SaltoSpaceMetadata|null $salto_space_metadata = null,
            /**
             * IDs of the spaces that the entrance is in.
             */
            public array|null $space_ids,
            /**
             * Visionline-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public AcsEntrance\VisionlineMetadata|null $visionline_metadata = null,
            /**
             * Warnings associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public array $warnings,
        ) {}
    }
}

namespace Seam\Resources\AcsEntrance {
    /**
     * Akiles-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                actions: array_map(
                    fn($a) => AkilesMetadata\Actions::from_json($a),
                    $json->actions ?? [],
                ),
                gadget_id: $json->gadget_id ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
            );
        }

        public function __construct(
            /**
             * Actions the gadget exposes (for example, open).
             */
            public array|null $actions = null,
            /**
             * ID of the Akiles gadget.
             */
            public string|null $gadget_id = null,
            /**
             * ID of the Akiles site the gadget belongs to.
             */
            public string|null $site_id = null,
            /**
             * Name of the Akiles site the gadget belongs to.
             */
            public string|null $site_name = null,
        ) {}
    }

    /**
     * ASSA ABLOY Vostio-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                door_name: $json->door_name ?? null,
                door_number: $json->door_number ?? null,
                door_type: $json->door_type ?? null,
                pms_id: $json->pms_id ?? null,
                stand_open: $json->stand_open ?? null,
            );
        }

        public function __construct(
            /**
             * Name of the door in the Vostio access system.
             */
            public string|null $door_name = null,
            /**
             * Number of the door in the Vostio access system.
             */
            public float|null $door_number = null,
            /**
             * Type of the door in the Vostio access system.
             */
            public string|null $door_type = null,
            /**
             * PMS ID of the door in the Vostio access system.
             */
            public string|null $pms_id = null,
            /**
             * Indicates whether keys are allowed to set the door in stand open mode in the Vostio access system.
             */
            public bool|null $stand_open = null,
        ) {}
    }

    /**
     * Avigilon Alta-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class AvigilonAltaMetadata
    {
        public static function from_json(mixed $json): AvigilonAltaMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                entry_name: $json->entry_name ?? null,
                entry_relays_total_count: $json->entry_relays_total_count ??
                    null,
                org_name: $json->org_name ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
                zone_id: $json->zone_id ?? null,
                zone_name: $json->zone_name ?? null,
            );
        }

        public function __construct(
            /**
             * Entry name for an Avigilon Alta system.
             */
            public string|null $entry_name = null,
            /**
             * Total count of entry relays for an Avigilon Alta system.
             */
            public float|null $entry_relays_total_count = null,
            /**
             * Organization name for an Avigilon Alta system.
             */
            public string|null $org_name = null,
            /**
             * Site ID for an Avigilon Alta system.
             */
            public float|null $site_id = null,
            /**
             * Site name for an Avigilon Alta system.
             */
            public string|null $site_name = null,
            /**
             * Zone ID for an Avigilon Alta system.
             */
            public float|null $zone_id = null,
            /**
             * Zone name for an Avigilon Alta system.
             */
            public string|null $zone_name = null,
        ) {}
    }

    /**
     * Brivo-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class BrivoMetadata
    {
        public static function from_json(mixed $json): BrivoMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_point_id: $json->access_point_id ?? null,
                site_id: $json->site_id ?? null,
                site_name: $json->site_name ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access point in the Brivo access system.
             */
            public string|null $access_point_id = null,
            /**
             * ID of the site that the access point belongs to.
             */
            public float|null $site_id = null,
            /**
             * Name of the site that the access point belongs to.
             */
            public string|null $site_name = null,
        ) {}
    }

    /**
     * dormakaba Ambiance-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class DormakabaAmbianceMetadata
    {
        public static function from_json(
            mixed $json,
        ): DormakabaAmbianceMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_point_name: $json->access_point_name ?? null,
            );
        }

        public function __construct(
            /**
             * Name of the access point in the dormakaba Ambiance access system.
             */
            public string|null $access_point_name = null,
        ) {}
    }

    /**
     * dormakaba Community-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class DormakabaCommunityMetadata
    {
        public static function from_json(
            mixed $json,
        ): DormakabaCommunityMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_point_profile: $json->access_point_profile ?? null,
            );
        }

        public function __construct(
            /**
             * Type of access point profile in the dormakaba Community access system.
             */
            public string|null $access_point_profile = null,
        ) {}
    }

    /**
     * Errors associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Hotek-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class HotekMetadata
    {
        public static function from_json(mixed $json): HotekMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                common_area_name: $json->common_area_name ?? null,
                common_area_number: $json->common_area_number ?? null,
                room_number: $json->room_number ?? null,
            );
        }

        public function __construct(
            /**
             * Display name of the entrance.
             */
            public string|null $common_area_name = null,
            /**
             * Display name of the entrance.
             */
            public string|null $common_area_number = null,
            /**
             * Room number of the entrance.
             */
            public string|null $room_number = null,
        ) {}
    }

    /**
     * Latch-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class LatchMetadata
    {
        public static function from_json(mixed $json): LatchMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                accessibility_type: $json->accessibility_type ?? null,
                door_name: $json->door_name ?? null,
                door_type: $json->door_type ?? null,
                is_connected: $json->is_connected ?? null,
            );
        }

        public function __construct(
            /**
             * Accessibility type in the Latch access system.
             */
            public string|null $accessibility_type = null,
            /**
             * Name of the door in the Latch access system.
             */
            public string|null $door_name = null,
            /**
             * Type of the door in the Latch access system.
             */
            public string|null $door_type = null,
            /**
             * Indicates whether the entrance is connected.
             */
            public bool|null $is_connected = null,
        ) {}
    }

    /**
     * Salto KS-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class SaltoKsMetadata
    {
        public static function from_json(mixed $json): SaltoKsMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                battery_level: $json->battery_level ?? null,
                door_name: $json->door_name ?? null,
                intrusion_alarm: $json->intrusion_alarm ?? null,
                left_open_alarm: $json->left_open_alarm ?? null,
                lock_type: $json->lock_type ?? null,
                locked_state: $json->locked_state ?? null,
                online: $json->online ?? null,
                privacy_mode: $json->privacy_mode ?? null,
            );
        }

        public function __construct(
            /**
             * Battery level of the door access device.
             */
            public string|null $battery_level = null,
            /**
             * Name of the door in the Salto KS access system.
             */
            public string|null $door_name = null,
            /**
             * Indicates whether an intrusion alarm is active on the door.
             */
            public bool|null $intrusion_alarm = null,
            /**
             * Indicates whether the door is left open.
             */
            public bool|null $left_open_alarm = null,
            /**
             * Type of the lock in the Salto KS access system.
             */
            public string|null $lock_type = null,
            /**
             * Locked state of the door in the Salto KS access system.
             */
            public string|null $locked_state = null,
            /**
             * Indicates whether the door access device is online.
             */
            public bool|null $online = null,
            /**
             * Indicates whether privacy mode is enabled for the lock.
             */
            public bool|null $privacy_mode = null,
        ) {}
    }

    /**
     * Salto Space-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class SaltoSpaceMetadata
    {
        public static function from_json(mixed $json): SaltoSpaceMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                audit_on_keys: $json->audit_on_keys ?? null,
                door_description: $json->door_description ?? null,
                door_id: $json->door_id ?? null,
                door_name: $json->door_name ?? null,
                room_description: $json->room_description ?? null,
                room_name: $json->room_name ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether AuditOnKeys is enabled for the door in the Salto Space access system.
             */
            public bool|null $audit_on_keys = null,
            /**
             * Description of the door in the Salto Space access system.
             */
            public string|null $door_description = null,
            /**
             * Door ID in the Salto Space access system.
             */
            public string|null $door_id = null,
            /**
             * Name of the door in the Salto Space access system.
             */
            public string|null $door_name = null,
            /**
             * Description of the room in the Salto Space access system.
             */
            public string|null $room_description = null,
            /**
             * Name of the room in the Salto Space access system.
             */
            public string|null $room_name = null,
        ) {}
    }

    /**
     * Visionline-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                door_category: $json->door_category ?? null,
                door_name: $json->door_name ?? null,
                profiles: array_map(
                    fn($p) => VisionlineMetadata\Profiles::from_json($p),
                    $json->profiles ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Category of the door in the Visionline access system.
             */
            public string|null $door_category = null,
            /**
             * Name of the door in the Visionline access system.
             */
            public string|null $door_name = null,
            /**
             * Profile for the door in the Visionline access system.
             */
            public array|null $profiles = null,
        ) {}
    }

    /**
     * Warnings associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
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
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\AcsEntrance\AkilesMetadata {
    /**
     * Actions the gadget exposes (for example, open).
     */
    class Actions
    {
        public static function from_json(mixed $json): Actions|null
        {
            if (!$json) {
                return null;
            }
            return new self(id: $json->id ?? null, name: $json->name ?? null);
        }

        public function __construct(
            /**
             * ID of the gadget action.
             */
            public string|null $id = null,
            /**
             * Name of the gadget action.
             */
            public string|null $name = null,
        ) {}
    }
}

namespace Seam\Resources\AcsEntrance\VisionlineMetadata {
    /**
     * Profile for the door in the Visionline access system.
     */
    class Profiles
    {
        public static function from_json(mixed $json): Profiles|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                visionline_door_profile_id: $json->visionline_door_profile_id ??
                    null,
                visionline_door_profile_type: $json->visionline_door_profile_type ??
                    null,
            );
        }

        public function __construct(
            /**
             * Door profile ID in the Visionline access system.
             */
            public string|null $visionline_door_profile_id = null,
            /**
             * Door profile type in the Visionline access system.
             */
            public string|null $visionline_door_profile_type = null,
        ) {}
    }
}
