<?php

namespace Seam\Objects;

class EventReason
{
    public static function from_json(mixed $json): EventReason|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message,
            reason_code: $json->reason_code,
        );
    }

    public function __construct(
        public string $message,
        public string $reason_code,
    ) {}
}
