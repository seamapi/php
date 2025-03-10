<?php

namespace Seam\Objects;

class ConnectedAccountErrors
{
    public static function from_json(mixed $json): ConnectedAccountErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            error_code: $json->error_code,
            is_connected_account_error: $json->is_connected_account_error,
            message: $json->message,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? ConnectedAccountSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
                )
                : null
        );
    }

    public function __construct(
        public string $created_at,
        public string $error_code,
        public bool $is_connected_account_error,
        public string $message,
        public ConnectedAccountSaltoKsMetadata|null $salto_ks_metadata
    ) {
    }
}
