<?php

namespace Seam\Objects;

class AccessCodeDormakabaOracodeMetadata
{
    public static function from_json(
        mixed $json,
    ): AccessCodeDormakabaOracodeMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            stay_id: $json->stay_id,
            is_cancellable: $json->is_cancellable ?? null,
            is_early_checkin_able: $json->is_early_checkin_able ?? null,
            is_extendable: $json->is_extendable ?? null,
            is_overridable: $json->is_overridable ?? null,
            site_name: $json->site_name ?? null,
            user_level_id: $json->user_level_id ?? null,
            user_level_name: $json->user_level_name ?? null,
        );
    }

    public function __construct(
        public float $stay_id,
        public bool|null $is_cancellable,
        public bool|null $is_early_checkin_able,
        public bool|null $is_extendable,
        public bool|null $is_overridable,
        public string|null $site_name,
        public string|null $user_level_id,
        public string|null $user_level_name,
    ) {}
}
