<?php

namespace Seam\Objects;

class AccessMethod
{
    public static function from_json(mixed $json): AccessMethod|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_method_id: $json->access_method_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            mode: $json->mode,
            workspace_id: $json->workspace_id,
            instant_key_url: $json->instant_key_url ?? null,
            is_card_encoding_required: $json->is_card_encoding_required ?? null,
            issued_at: $json->issued_at ?? null,
        );
    }

    public function __construct(
        public string $access_method_id,
        public string $created_at,
        public string $display_name,
        public string $mode,
        public string $workspace_id,
        public string|null $instant_key_url,
        public bool|null $is_card_encoding_required,
        public string|null $issued_at,
    ) {}
}
