<?php

namespace Seam\Objects;

class ConnectedAccount
{
    public static function from_json(mixed $json): ConnectedAccount|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            user_identifier: $json->user_identifier ?? null,
            account_type: $json->account_type ?? null,
            account_type_display_name: $json->account_type_display_name ?? null,
            errors: $json->errors ?? null,
            warnings: $json->warnings ?? null,
            custom_metadata: $json->custom_metadata ?? null
        );
    }

    public function __construct(
        public string|null $connected_account_id,
        public string|null $created_at,
        public mixed $user_identifier,
        public string|null $account_type,
        public string|null $account_type_display_name,
        public mixed $errors,
        public mixed $warnings,
        public mixed $custom_metadata
    ) {
    }
}
