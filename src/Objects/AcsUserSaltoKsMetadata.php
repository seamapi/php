<?php

namespace Seam\Objects;

class AcsUserSaltoKsMetadata
{
    public static function from_json(mixed $json): AcsUserSaltoKsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(is_subscribed: $json->is_subscribed ?? null);
    }

    public function __construct(public bool|null $is_subscribed) {}
}
