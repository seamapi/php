<?php

namespace Seam\Objects;

class UnmanagedAcsUserAccessSchedule
{
    public static function from_json(
        mixed $json
    ): UnmanagedAcsUserAccessSchedule|null {
        if (!$json) {
            return null;
        }
        return new self(
            starts_at: $json->starts_at,
            ends_at: $json->ends_at ?? null
        );
    }

    public function __construct(
        public string $starts_at,
        public string|null $ends_at
    ) {
    }
}
