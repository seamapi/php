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
            device_id: $json->device_id,
            error_code: $json->error_code,
            message: $json->message,
        );
    }

    public function __construct(
        public string $device_id,
        public string $error_code,
        public string $message,
    ) {}
}
