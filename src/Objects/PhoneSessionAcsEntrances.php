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
            acs_entrance_id: $json->acs_entrance_id,
            acs_system_id: $json->acs_system_id,
            connected_account_id: $json->connected_account_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => PhoneSessionErrors::from_json($e),
                $json->errors ?? []
            ),
            assa_abloy_vostio_metadata: isset($json->assa_abloy_vostio_metadata)
                ? PhoneSessionAssaAbloyVostioMetadata::from_json(
                    $json->assa_abloy_vostio_metadata
                )
                : null,
            dormakaba_community_metadata: isset(
                $json->dormakaba_community_metadata
            )
                ? PhoneSessionDormakabaCommunityMetadata::from_json(
                    $json->dormakaba_community_metadata
                )
                : null,
            latch_metadata: isset($json->latch_metadata)
                ? PhoneSessionLatchMetadata::from_json($json->latch_metadata)
                : null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? PhoneSessionSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
                )
                : null,
            salto_space_metadata: isset($json->salto_space_metadata)
                ? PhoneSessionSaltoSpaceMetadata::from_json(
                    $json->salto_space_metadata
                )
                : null,
            visionline_metadata: isset($json->visionline_metadata)
                ? PhoneSessionVisionlineMetadata::from_json(
                    $json->visionline_metadata
                )
                : null
        );
    }

    public function __construct(
        public string $acs_entrance_id,
        public string $acs_system_id,
        public string $connected_account_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public PhoneSessionAssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata,
        public PhoneSessionDormakabaCommunityMetadata|null $dormakaba_community_metadata,
        public PhoneSessionLatchMetadata|null $latch_metadata,
        public PhoneSessionSaltoKsMetadata|null $salto_ks_metadata,
        public PhoneSessionSaltoSpaceMetadata|null $salto_space_metadata,
        public PhoneSessionVisionlineMetadata|null $visionline_metadata
    ) {
    }
}
