<?php

namespace Seam\Objects;

class UnmanagedAccessCodeSaltoKsMetadata
{
    public static function from_json(
        mixed $json
    ): UnmanagedAccessCodeSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => UnmanagedAccessCodeSites::from_json($s),
                $json->sites ?? []
            )
        );
    }

    public function __construct(public array $sites)
    {
    }
}
