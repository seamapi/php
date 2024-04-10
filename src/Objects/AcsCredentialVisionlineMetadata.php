<?php

namespace Seam\Objects;

class AcsCredentialVisionlineMetadata
{
    
    public static function from_json(mixed $json): AcsCredentialVisionlineMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            joiner_acs_credential_ids: $json->joiner_acs_credential_ids ?? null,
            guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
            common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
        );
    }
  

    
    public function __construct(
        public array | null $joiner_acs_credential_ids,
        public array | null $guest_acs_entrance_ids,
        public array | null $common_acs_entrance_ids,
    ) {
    }
  
}
