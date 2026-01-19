<?php

namespace Seam\Objects;

class EventConnectedAccountErrors
{
    public static function from_json(
        mixed $json,
    ): EventConnectedAccountErrors|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            error_code: $json->error_code,
            message: $json->message,
            is_bridge_error: $json->is_bridge_error ?? null,
            is_connected_account_error: $json->is_connected_account_error ??
                null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? EventSaltoKsMetadata::from_json($json->salto_ks_metadata)
                : null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $error_code,
        public string $message,
        public bool|null $is_bridge_error,
        public bool|null $is_connected_account_error,
        public EventSaltoKsMetadata|null $salto_ks_metadata,
    ) {}
}
