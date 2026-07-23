<?php

namespace Seam\Objects;

class UnmanagedAcsUserSaltoKsMetadata
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAcsUserSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(is_subscribed: $json->is_subscribed ?? null);
    }

    public function __construct(public bool|null $is_subscribed) {}
}
