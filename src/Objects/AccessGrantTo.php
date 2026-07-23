<?php

namespace Seam\Objects;

class AccessGrantTo
{
    public static function from_json(mixed $json): AccessGrantTo|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            common_code_key: $json->common_code_key ?? null,
            device_ids: $json->device_ids ?? null,
            ends_at: $json->ends_at ?? null,
            starts_at: $json->starts_at ?? null,
        );
    }

    public function __construct(
        public string|null $common_code_key,
        public array|null $device_ids,
        public string|null $ends_at,
        public string|null $starts_at,
    ) {}
}
