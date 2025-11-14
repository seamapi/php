<?php

namespace Seam\Objects;

class AcsUserSaltoSpaceMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsUserSaltoSpaceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(audit_openings: $json->audit_openings ?? null);
    }

    public function __construct(public bool|null $audit_openings) {}
}
