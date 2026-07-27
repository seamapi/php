<?php

namespace Seam\Resources;

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
        public string|null $acs_entrance_id,
        public string|null $acs_system_id,
        public AcsEntranceAkilesMetadata|null $akiles_metadata,
        public AcsEntranceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public AcsEntranceAvigilonAltaMetadata|null $avigilon_alta_metadata,
        public AcsEntranceBrivoMetadata|null $brivo_metadata,
        public bool|null $can_belong_to_reservation,
        public bool|null $can_unlock_with_card,
        public bool|null $can_unlock_with_cloud_key,
        public bool|null $can_unlock_with_code,
        public bool|null $can_unlock_with_mobile_key,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public AcsEntranceDormakabaAmbianceMetadata|null $dormakaba_ambiance_metadata,
        public AcsEntranceDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        public array $errors,
        public AcsEntranceHotekMetadata|null $hotek_metadata,
        public bool|null $is_locked,
        public AcsEntranceLatchMetadata|null $latch_metadata,
        public AcsEntranceSaltoKsMetadata|null $salto_ks_metadata,
        public AcsEntranceSaltoSpaceMetadata|null $salto_space_metadata,
        public array|null $space_ids,
        public AcsEntranceVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
    ) {}
}

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
        public string|null $id,
        public string|null $name,
    ) {}
}

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
        public array $actions,
        public string|null $gadget_id,
        public string|null $site_id,
        public string|null $site_name,
    ) {}
}

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
        public string|null $door_name,
        public float|null $door_number,
        public string|null $door_type,
        public string|null $pms_id,
        public bool|null $stand_open,
    ) {}
}

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
        public string|null $entry_name,
        public float|null $entry_relays_total_count,
        public string|null $org_name,
        public float|null $site_id,
        public string|null $site_name,
        public float|null $zone_id,
        public string|null $zone_name,
    ) {}
}

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
        public string|null $access_point_id,
        public float|null $site_id,
        public string|null $site_name,
    ) {}
}

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

    public function __construct(public string|null $access_point_name) {}
}

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

    public function __construct(public string|null $access_point_profile) {}
}

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
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

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
        public string|null $common_area_name,
        public string|null $common_area_number,
        public string|null $room_number,
    ) {}
}

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
        public string|null $accessibility_type,
        public string|null $door_name,
        public string|null $door_type,
        public bool|null $is_connected,
    ) {}
}

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
        public string|null $visionline_door_profile_id,
        public string|null $visionline_door_profile_type,
    ) {}
}

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
        public string|null $battery_level,
        public string|null $door_name,
        public bool|null $intrusion_alarm,
        public bool|null $left_open_alarm,
        public string|null $lock_type,
        public string|null $locked_state,
        public bool|null $online,
        public bool|null $privacy_mode,
    ) {}
}

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
        public bool|null $audit_on_keys,
        public string|null $door_description,
        public string|null $door_id,
        public string|null $door_name,
        public string|null $room_description,
        public string|null $room_name,
    ) {}
}

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
        public string|null $door_category,
        public string|null $door_name,
        public array $profiles,
    ) {}
}

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
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
