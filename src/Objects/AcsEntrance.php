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
            latch_metadata: isset($json->latch_metadata)
                ? AcsEntranceLatchMetadata::from_json($json->latch_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? AcsEntranceSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
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
        public AcsEntranceLatchMetadata|null $latch_metadata,
        public AcsEntranceSaltoKsMetadata|null $salto_ks_metadata,
        public AcsEntranceVisionlineMetadata|null $visionline_metadata
    ) {
    }
}
