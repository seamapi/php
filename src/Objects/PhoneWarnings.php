<?php

namespace Seam\Objects;

class PhoneWarnings
{
    public static function from_json(mixed $json): PhoneWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
