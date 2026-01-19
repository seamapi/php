<?php

namespace Seam\Objects;

class EventSaltoKsMetadata
{
    public static function from_json(mixed $json): EventSaltoKsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            sites: array_map(
                fn($s) => EventSites::from_json($s),
                $json->sites ?? [],
            ),
        );
    }

    public function __construct(public array $sites) {}
}
