<?php

namespace Seam\Objects;

class AcsSystem
{
    public static function from_json(mixed $json): AcsSystem|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_system_id: $json->acs_system_id ?? null,
            external_type: $json->external_type ?? null,
            external_type_display_name: $json->external_type_display_name ??
                null,
            system_type: $json->system_type ?? null,
            system_type_display_name: $json->system_type_display_name ?? null,
            name: $json->name ?? null,
            created_at: $json->created_at ?? null,
            connected_account_ids: $json->connected_account_ids ?? null
        );
    }

    public function __construct(
        public string|null $acs_system_id,
        public string|null $external_type,
        public string|null $external_type_display_name,
        public string|null $system_type,
        public string|null $system_type_display_name,
        public string|null $name,
        public string|null $created_at,
        public array|null $connected_account_ids
    ) {
    }
}
