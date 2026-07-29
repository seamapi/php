<?php

namespace Seam\Resources;

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
                ? AcsEntranceAkilesMetadata::from_json($json->akiles_metadata)
                : null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? AcsEntranceAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            avigilon_alta_metadata: isset($json->avigilon_alta_metadata)
                ? AcsEntranceAvigilonAltaMetadata::from_json(
                    $json->avigilon_alta_metadata,
                )
                : null,
            brivo_metadata: isset($json->brivo_metadata)
                ? AcsEntranceBrivoMetadata::from_json($json->brivo_metadata)
                : null,
            can_belong_to_reservation: $json->can_belong_to_reservation ?? null,
            can_unlock_with_card: $json->can_unlock_with_card ?? null,
            can_unlock_with_cloud_key: $json->can_unlock_with_cloud_key ?? null,
            can_unlock_with_code: $json->can_unlock_with_code ?? null,
            can_unlock_with_mobile_key: $json->can_unlock_with_mobile_key ??
                null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            dormakaba_ambiance_metadata: isset(
                $json->dormakaba_ambiance_metadata,
            )
                ? AcsEntranceDormakabaAmbianceMetadata::from_json(
                    $json->dormakaba_ambiance_metadata,
                )
                : null,
            dormakaba_community_metadata: isset(
                $json->dormakaba_community_metadata,
            )
                ? AcsEntranceDormakabaCommunityMetadata::from_json(
                    $json->dormakaba_community_metadata,
                )
                : null,
            errors: array_map(
                fn($e) => AcsEntranceErrors::from_json($e),
                $json->errors ?? [],
            ),
            hotek_metadata: isset($json->hotek_metadata)
                ? AcsEntranceHotekMetadata::from_json($json->hotek_metadata)
                : null,
            is_locked: $json->is_locked ?? null,
            latch_metadata: isset($json->latch_metadata)
                ? AcsEntranceLatchMetadata::from_json($json->latch_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? AcsEntranceSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata,
                )
                : null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? AcsEntranceSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata,
                )
                : null,
            space_ids: $json->space_ids ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsEntranceVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => AcsEntranceWarnings::from_json($w),
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
        public AcsEntranceAkilesMetadata|null $akiles_metadata,
        /**
         * ASSA ABLOY Vostio-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        /**
         * Avigilon Alta-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceAvigilonAltaMetadata|null $avigilon_alta_metadata,
        /**
         * Brivo-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceBrivoMetadata|null $brivo_metadata,
        /**
         * Indicates whether the ACS entrance can belong to a reservation via an access_grant.reservation_key.
         */
        public bool|null $can_belong_to_reservation,
        /**
         * Indicates whether the ACS entrance can be unlocked with card credentials.
         */
        public bool|null $can_unlock_with_card,
        /**
         * Indicates whether the ACS entrance can be unlocked with cloud key credentials.
         */
        public bool|null $can_unlock_with_cloud_key,
        /**
         * Indicates whether the ACS entrance can be unlocked with pin codes.
         */
        public bool|null $can_unlock_with_code,
        /**
         * Indicates whether the ACS entrance can be unlocked with mobile key credentials.
         */
        public bool|null $can_unlock_with_mobile_key,
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
        public AcsEntranceDormakabaAmbianceMetadata|null $dormakaba_ambiance_metadata,
        /**
         * dormakaba Community-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        /**
         * Errors associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public array $errors,
        /**
         * Hotek-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceHotekMetadata|null $hotek_metadata,
        /**
         * Indicates whether the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) is currently locked.
         */
        public bool|null $is_locked,
        /**
         * Latch-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceLatchMetadata|null $latch_metadata,
        /**
         * Salto KS-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceSaltoKsMetadata|null $salto_ks_metadata,
        /**
         * Salto Space-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceSaltoSpaceMetadata|null $salto_space_metadata,
        /**
         * IDs of the spaces that the entrance is in.
         */
        public array|null $space_ids,
        /**
         * Visionline-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public AcsEntranceVisionlineMetadata|null $visionline_metadata,
        /**
         * Warnings associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
         */
        public array $warnings,
    ) {}
}

/**
 * Actions the gadget exposes (for example, open).
 */
class AcsEntranceActions
{
    public static function from_json(mixed $json): AcsEntranceActions|null
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
        public string|null $id,
        /**
         * Name of the gadget action.
         */
        public string|null $name,
    ) {}
}

/**
 * Akiles-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceAkilesMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceAkilesMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            actions: array_map(
                fn($a) => AcsEntranceActions::from_json($a),
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
        public array $actions,
        /**
         * ID of the Akiles gadget.
         */
        public string|null $gadget_id,
        /**
         * ID of the Akiles site the gadget belongs to.
         */
        public string|null $site_id,
        /**
         * Name of the Akiles site the gadget belongs to.
         */
        public string|null $site_name,
    ) {}
}

/**
 * ASSA ABLOY Vostio-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceAssaAbloyVostioMetadata|null {
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
        public string|null $door_name,
        /**
         * Number of the door in the Vostio access system.
         */
        public float|null $door_number,
        /**
         * Type of the door in the Vostio access system.
         */
        public string|null $door_type,
        /**
         * PMS ID of the door in the Vostio access system.
         */
        public string|null $pms_id,
        /**
         * Indicates whether keys are allowed to set the door in stand open mode in the Vostio access system.
         */
        public bool|null $stand_open,
    ) {}
}

/**
 * Avigilon Alta-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceAvigilonAltaMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceAvigilonAltaMetadata|null {
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
        /**
         * Entry name for an Avigilon Alta system.
         */
        public string|null $entry_name,
        /**
         * Total count of entry relays for an Avigilon Alta system.
         */
        public float|null $entry_relays_total_count,
        /**
         * Organization name for an Avigilon Alta system.
         */
        public string|null $org_name,
        /**
         * Site ID for an Avigilon Alta system.
         */
        public float|null $site_id,
        /**
         * Site name for an Avigilon Alta system.
         */
        public string|null $site_name,
        /**
         * Zone ID for an Avigilon Alta system.
         */
        public float|null $zone_id,
        /**
         * Zone name for an Avigilon Alta system.
         */
        public string|null $zone_name,
    ) {}
}

/**
 * Brivo-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceBrivoMetadata
{
    public static function from_json(mixed $json): AcsEntranceBrivoMetadata|null
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
        public string|null $access_point_id,
        /**
         * ID of the site that the access point belongs to.
         */
        public float|null $site_id,
        /**
         * Name of the site that the access point belongs to.
         */
        public string|null $site_name,
    ) {}
}

/**
 * dormakaba Ambiance-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceDormakabaAmbianceMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceDormakabaAmbianceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(access_point_name: $json->access_point_name ?? null);
    }

    public function __construct(
        /**
         * Name of the access point in the dormakaba Ambiance access system.
         */
        public string|null $access_point_name,
    ) {}
}

/**
 * dormakaba Community-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceDormakabaCommunityMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceDormakabaCommunityMetadata|null {
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
        public string|null $access_point_profile,
    ) {}
}

/**
 * Errors associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceErrors
{
    public static function from_json(mixed $json): AcsEntranceErrors|null
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
class AcsEntranceHotekMetadata
{
    public static function from_json(mixed $json): AcsEntranceHotekMetadata|null
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
        public string|null $common_area_name,
        /**
         * Display name of the entrance.
         */
        public string|null $common_area_number,
        /**
         * Room number of the entrance.
         */
        public string|null $room_number,
    ) {}
}

/**
 * Latch-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceLatchMetadata
{
    public static function from_json(mixed $json): AcsEntranceLatchMetadata|null
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
        public string|null $accessibility_type,
        /**
         * Name of the door in the Latch access system.
         */
        public string|null $door_name,
        /**
         * Type of the door in the Latch access system.
         */
        public string|null $door_type,
        /**
         * Indicates whether the entrance is connected.
         */
        public bool|null $is_connected,
    ) {}
}

/**
 * Profile for the door in the Visionline access system.
 */
class AcsEntranceProfiles
{
    public static function from_json(mixed $json): AcsEntranceProfiles|null
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
        public string|null $visionline_door_profile_id,
        /**
         * Door profile type in the Visionline access system.
         */
        public string|null $visionline_door_profile_type,
    ) {}
}

/**
 * Salto KS-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceSaltoKsMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceSaltoKsMetadata|null {
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
        public string|null $battery_level,
        /**
         * Name of the door in the Salto KS access system.
         */
        public string|null $door_name,
        /**
         * Indicates whether an intrusion alarm is active on the door.
         */
        public bool|null $intrusion_alarm,
        /**
         * Indicates whether the door is left open.
         */
        public bool|null $left_open_alarm,
        /**
         * Type of the lock in the Salto KS access system.
         */
        public string|null $lock_type,
        /**
         * Locked state of the door in the Salto KS access system.
         */
        public string|null $locked_state,
        /**
         * Indicates whether the door access device is online.
         */
        public bool|null $online,
        /**
         * Indicates whether privacy mode is enabled for the lock.
         */
        public bool|null $privacy_mode,
    ) {}
}

/**
 * Salto Space-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceSaltoSpaceMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceSaltoSpaceMetadata|null {
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
        public bool|null $audit_on_keys,
        /**
         * Description of the door in the Salto Space access system.
         */
        public string|null $door_description,
        /**
         * Door ID in the Salto Space access system.
         */
        public string|null $door_id,
        /**
         * Name of the door in the Salto Space access system.
         */
        public string|null $door_name,
        /**
         * Description of the room in the Salto Space access system.
         */
        public string|null $room_description,
        /**
         * Name of the room in the Salto Space access system.
         */
        public string|null $room_name,
    ) {}
}

/**
 * Visionline-specific metadata associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_category: $json->door_category ?? null,
            door_name: $json->door_name ?? null,
            profiles: array_map(
                fn($p) => AcsEntranceProfiles::from_json($p),
                $json->profiles ?? [],
            ),
        );
    }

    public function __construct(
        /**
         * Category of the door in the Visionline access system.
         */
        public string|null $door_category,
        /**
         * Name of the door in the Visionline access system.
         */
        public string|null $door_name,
        /**
         * Profile for the door in the Visionline access system.
         */
        public array $profiles,
    ) {}
}

/**
 * Warnings associated with the [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
 */
class AcsEntranceWarnings
{
    public static function from_json(mixed $json): AcsEntranceWarnings|null
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
