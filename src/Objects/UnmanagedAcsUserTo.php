<?php

namespace Seam\Objects;

class UnmanagedAcsUserTo
{
    public static function from_json(mixed $json): UnmanagedAcsUserTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(acs_credential_id: $json->acs_credential_id ?? null);
    }

    public function __construct(public string|null $acs_credential_id) {}
}
