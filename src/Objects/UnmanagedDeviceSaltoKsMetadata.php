<?php

namespace Seam\Objects;

class UnmanagedDeviceSaltoKsMetadata
{
    public static function from_json(
        mixed $json
    ): UnmanagedDeviceSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => UnmanagedDeviceSites::from_json($s),
                $json->sites ?? []
            )
        );
    }

    public function __construct(public array $sites)
    {
    }
}
