<?php

namespace Seam\Objects;

class AcsAccessGroupFrom
{
    public static function from_json(mixed $json): AcsAccessGroupFrom|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_entrance_id: $json->acs_entrance_id ?? null,
            acs_user_id: $json->acs_user_id ?? null,
            ends_at: $json->ends_at ?? null,
            name: $json->name ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $acs_entrance_id,
        public string|null $acs_user_id,
        public string|null $ends_at,
        public string|null $name,
        public string|null $starts_at,
    ) {}
}
