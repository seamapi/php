<?php

namespace Seam\Objects;

class UnmanagedAcsAccessGroupTo
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAcsAccessGroupTo|null {
        if (!$json) {
            return null;
        }
        return new self(acs_entrance_id: $json->acs_entrance_id ?? null);
    }

    public function __construct(public string|null $acs_entrance_id) {}
}
