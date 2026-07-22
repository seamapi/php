<?php

namespace Seam\Objects;

class UnmanagedAcsCredentialWarnings
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAcsCredentialWarnings|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
