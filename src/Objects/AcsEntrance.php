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
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsEntranceErrors::from_json($e),
                $json->errors ?? []
            ),
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? AcsEntranceAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata
                )
                : null,
            dormakaba_community_metadata: isset(
                $json->dormakaba_community_metadata
            )
                ? AcsEntranceDormakabaCommunityMetadata::from_json(
                    $json->dormakaba_community_metadata
                )
                : null,
            latch_metadata: isset($json->latch_metadata)
                ? AcsEntranceLatchMetadata::from_json($json->latch_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? AcsEntranceSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
                )
                : null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? AcsEntranceSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata
                )
                : null,
            visionline_metadata: isset($json->visionline_metadata)
                ? AcsEntranceVisionlineMetadata::from_json(
                    $json->visionline_metadata
                )
                : null
        );
    }

    public function __construct(
        public string $acs_entrance_id,
        public string $acs_system_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public AcsEntranceAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public AcsEntranceDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        public AcsEntranceLatchMetadata|null $latch_metadata,
        public AcsEntranceSaltoKsMetadata|null $salto_ks_metadata,
        public AcsEntranceSaltoSpaceMetadata|null $salto_space_metadata,
        public AcsEntranceVisionlineMetadata|null $visionline_metadata
    ) {
    }
}
