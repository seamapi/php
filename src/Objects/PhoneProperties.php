<?php

namespace Seam\Objects;

class PhoneProperties
{
    
    public static function from_json(mixed $json): PhoneProperties|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            assa_abloy_credential_service_metadata: isset($json->assa_abloy_credential_service_metadata) ? PhoneAssaAbloyCredentialServiceMetadata::from_json($json->assa_abloy_credential_service_metadata) : null,
        );
    }
  

    
    public function __construct(
        public PhoneAssaAbloyCredentialServiceMetadata | null $assa_abloy_credential_service_metadata,
    ) {
    }
  
}
