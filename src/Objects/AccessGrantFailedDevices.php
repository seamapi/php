<?php

namespace Seam\Objects;

class AccessGrantFailedDevices
{
    public static function from_json(mixed $json): AccessGrantFailedDevices|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            device_id: $json->device_id ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $device_id,
        public string|null $error_code,
        public string|null $message,
    ) {}
}
