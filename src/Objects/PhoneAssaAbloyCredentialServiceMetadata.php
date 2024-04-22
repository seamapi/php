<?php

namespace Seam\Objects;

class PhoneAssaAbloyCredentialServiceMetadata
{
    public static function from_json(
        mixed $json
    ): PhoneAssaAbloyCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            endpoints: array_map(
                fn($e) => PhoneEndpoints::from_json($e),
                $json->endpoints ?? []
            ),
            has_active_endpoint: $json->has_active_endpoint
        );
    }

    public function __construct(
        public array $endpoints,
        public bool $has_active_endpoint
    ) {
    }
}
