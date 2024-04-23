<?php

namespace Seam\Objects;

class AcsUserAccessSchedule
{
    public static function from_json(mixed $json): AcsUserAccessSchedule|null
    {
        if (!$json) {
            return null;
        }
        return new self(ends_at: $json->ends_at, starts_at: $json->starts_at);
    }

    public function __construct(
        public string $ends_at,
        public string $starts_at
    ) {
    }
}
