<?php

namespace Seam\Objects;

class DeviceWarnings
{
    public static function from_json(mixed $json): DeviceWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            warning_code: $json->warning_code
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $warning_code
    ) {
    }
}
