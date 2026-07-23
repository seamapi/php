<?php

namespace Seam\Objects;

class AcsSystemWarnings
{
    public static function from_json(mixed $json): AcsSystemWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            misconfigured_acs_entrance_ids: $json->misconfigured_acs_entrance_ids ??
                null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public array|null $misconfigured_acs_entrance_ids,
        public string|null $warning_code,
    ) {}
}
