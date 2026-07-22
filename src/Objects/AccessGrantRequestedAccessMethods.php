<?php

namespace Seam\Objects;

class AccessGrantRequestedAccessMethods
{
    public static function from_json(
        mixed $json,
    ): AccessGrantRequestedAccessMethods|null {
        if (!$json) {
            return null;
        }
        return new self(
            code: $json->code ?? null,
            created_access_method_ids: $json->created_access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            instant_key_max_use_count: $json->instant_key_max_use_count ?? null,
            mode: $json->mode ?? null,
        );
    }

    public function __construct(
        public string|null $code,
        public array|null $created_access_method_ids,
        public string|null $created_at,
        public string|null $display_name,
        public float|null $instant_key_max_use_count,
        public string|null $mode,
    ) {}
}
