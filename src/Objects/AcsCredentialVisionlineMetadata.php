<?php

namespace Seam\Objects;

class AcsCredentialVisionlineMetadata
{
    public static function from_json(
        mixed $json
    ): AcsCredentialVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            card_function_type: $json->card_function_type,
            card_id: $json->card_id ?? null,
            common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
            credential_id: $json->credential_id ?? null,
            guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
            is_valid: $json->is_valid ?? null,
            joiner_acs_credential_ids: $json->joiner_acs_credential_ids ?? null
        );
    }

    public function __construct(
        public string $card_function_type,
        public string|null $card_id,
        public array|null $common_acs_entrance_ids,
        public string|null $credential_id,
        public array|null $guest_acs_entrance_ids,
        public bool|null $is_valid,
        public array|null $joiner_acs_credential_ids
    ) {
    }
}
