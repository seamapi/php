<?php

namespace Seam\Objects;

class DeviceAssaAbloyCredentialServiceMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceAssaAbloyCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            endpoints: array_map(
                fn($e) => DeviceEndpoints::from_json($e),
                $json->endpoints ?? [],
            ),
            has_active_endpoint: $json->has_active_endpoint ?? null,
        );
    }

    public function __construct(
        public array|null $endpoints,
        public bool|null $has_active_endpoint,
    ) {}
}
