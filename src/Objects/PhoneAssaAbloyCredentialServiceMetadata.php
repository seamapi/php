<?php

namespace Seam\Objects;

class PhoneAssaAbloyCredentialServiceMetadata
{
    
    public static function from_json(mixed $json): PhoneAssaAbloyCredentialServiceMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            has_active_endpoint: $json->has_active_endpoint,
            endpoints: array_map(
          fn ($e) => PhoneEndpoints::from_json($e),
          $json->endpoints ?? []
        ),
        );
    }
  

    
    public function __construct(
        public bool $has_active_endpoint,
        public array $endpoints,
    ) {
    }
  
}
