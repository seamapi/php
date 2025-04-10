<?php

namespace Seam\Objects;

class UnmanagedAcsUserTo
{
    public static function from_json(mixed $json): UnmanagedAcsUserTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null
        );
    }

    public function __construct(public string|null $acs_access_group_id)
    {
    }
}
