<?php

namespace Seam\Objects;

class ConnectedAccountSaltoKsMetadata
{
    public static function from_json(
        mixed $json
    ): ConnectedAccountSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => ConnectedAccountSites::from_json($s),
                $json->sites ?? []
            )
        );
    }

    public function __construct(public array $sites)
    {
    }
}
