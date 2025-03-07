<?php

namespace Seam\Objects;

class AccessCodeSaltoKsMetadata
{
    public static function from_json(
        mixed $json
    ): AccessCodeSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => AccessCodeSites::from_json($s),
                $json->sites ?? []
            )
        );
    }

    public function __construct(public array $sites)
    {
    }
}
