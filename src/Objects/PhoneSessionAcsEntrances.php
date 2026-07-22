<?php

namespace Seam\Objects;

class PhoneSessionAcsEntrances
{
    public static function from_json(mixed $json): PhoneSessionAcsEntrances|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_id: $json->acs_entrance_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? PhoneSessionAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            avigilon_alta_metadata: isset($json->avigilon_alta_metadata)
                ? PhoneSessionAvigilonAltaMetadata::from_json(
                    $json->avigilon_alta_metadata,
                )
                : null,
            brivo_metadata: isset($json->brivo_metadata)
                ? PhoneSessionBrivoMetadata::from_json($json->brivo_metadata)
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
                ? PhoneSessionDormakabaAmbianceMetadata::from_json(
                    $json->dormakaba_ambiance_metadata,
                )
                : null,
            dormakaba_community_metadata: isset(
                $json->dormakaba_community_metadata,
            )
                ? PhoneSessionDormakabaCommunityMetadata::from_json(
                    $json->dormakaba_community_metadata,
                )
                : null,
            errors: array_map(
                fn($e) => PhoneSessionErrors::from_json($e),
                $json->errors ?? [],
            ),
            hotek_metadata: isset($json->hotek_metadata)
                ? PhoneSessionHotekMetadata::from_json($json->hotek_metadata)
                : null,
            is_locked: $json->is_locked ?? null,
            latch_metadata: isset($json->latch_metadata)
                ? PhoneSessionLatchMetadata::from_json($json->latch_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? PhoneSessionSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata,
                )
                : null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? PhoneSessionSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata,
                )
                : null,
            space_ids: $json->space_ids ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? PhoneSessionVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
            warnings: array_map(
                fn($w) => PhoneSessionWarnings::from_json($w),
                $json->warnings ?? [],
            ),
        );
    }

    public function __construct(
        public string|null $acs_entrance_id,
        public string|null $acs_system_id,
        public PhoneSessionAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public PhoneSessionAvigilonAltaMetadata|null $avigilon_alta_metadata,
        public PhoneSessionBrivoMetadata|null $brivo_metadata,
        public bool|null $can_belong_to_reservation,
        public bool|null $can_unlock_with_card,
        public bool|null $can_unlock_with_cloud_key,
        public bool|null $can_unlock_with_code,
        public bool|null $can_unlock_with_mobile_key,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public PhoneSessionDormakabaAmbianceMetadata|null $dormakaba_ambiance_metadata,
        public PhoneSessionDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        public array $errors,
        public PhoneSessionHotekMetadata|null $hotek_metadata,
        public bool|null $is_locked,
        public PhoneSessionLatchMetadata|null $latch_metadata,
        public PhoneSessionSaltoKsMetadata|null $salto_ks_metadata,
        public PhoneSessionSaltoSpaceMetadata|null $salto_space_metadata,
        public array|null $space_ids,
        public PhoneSessionVisionlineMetadata|null $visionline_metadata,
        public array $warnings,
    ) {}
}
