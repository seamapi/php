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
            created_access_method_ids: $json->created_access_method_ids,
            created_at: $json->created_at,
            display_name: $json->display_name,
            mode: $json->mode,
        );
    }

    public function __construct(
        public array $created_access_method_ids,
        public string $created_at,
        public string $display_name,
        public string $mode,
    ) {}
}
