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
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            salto_ks_metadata: isset($json->salto_ks_metadata)
                ? ConnectedAccountSaltoKsMetadata::from_json(
                    $json->salto_ks_metadata,
                )
                : null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public ConnectedAccountSaltoKsMetadata|null $salto_ks_metadata,
        public string|null $warning_code,
    ) {}
}
