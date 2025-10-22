<?php

namespace Seam\Objects;

class AcsEntrance
{
    public static function from_json(mixed $json): AcsEntrance|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_id: $json->acs_entrance_id,
            acs_system_id: $json->acs_system_id,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsEntranceErrors::from_json($e),
                $json->errors ?? [],
            ),
            space_ids: $json->space_ids,
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? AcsEntranceAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata,
                )
                : null,
            can_belong_to_reservation: $json->can_belong_to_reservation ?? null,
            can_unlock_with_card: $json->can_unlock_with_card ?? null,
            can_unlock_with_code: $json->can_unlock_with_code ?? null,
            can_unlock_with_mobile_key: $json->can_unlock_with_mobile_key ??
                null,
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
            hotek_metadata: isset($json->hotek_metadata)
                ? AcsEntranceHotekMetadata::from_json($json->hotek_metadata)
                : null,
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
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsEntranceVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
        );
    }

    public function __construct(
        public string $acs_entrance_id,
        public string $acs_system_id,
        public string $connected_account_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public array $space_ids,
        public AcsEntranceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public bool|null $can_belong_to_reservation,
        public bool|null $can_unlock_with_card,
        public bool|null $can_unlock_with_code,
        public bool|null $can_unlock_with_mobile_key,
        public AcsEntranceDormakabaAmbianceMetadata|null $dormakaba_ambiance_metadata,
        public AcsEntranceDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        public AcsEntranceHotekMetadata|null $hotek_metadata,
        public AcsEntranceLatchMetadata|null $latch_metadata,
        public AcsEntranceSaltoKsMetadata|null $salto_ks_metadata,
        public AcsEntranceSaltoSpaceMetadata|null $salto_space_metadata,
        public AcsEntranceVisionlineMetadata|null $visionline_metadata,
    ) {}
}
