<?php

namespace Seam\Objects;

class DeviceSaltoKsMetadata
{
    public static function from_json(mixed $json): DeviceSaltoKsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => DeviceSites::from_json($s),
                $json->sites ?? []
            )
        );
    }

    public function __construct(public array $sites)
    {
    }
}
