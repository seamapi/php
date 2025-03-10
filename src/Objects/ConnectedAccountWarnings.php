<?php

namespace Seam\Objects;

class ConnectedAccountWarnings
{
    public static function from_json(mixed $json): ConnectedAccountWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? ConnectedAccountSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata
                )
                : null
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code,
        public ConnectedAccountSaltoKsMetadata|null $salto_ks_metadata
    ) {
    }
}
