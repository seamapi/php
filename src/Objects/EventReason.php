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
            message: $json->message ?? null,
            reason_code: $json->reason_code ?? null,
        );
    }

    public function __construct(
        public string|null $message,
        public string|null $reason_code,
    ) {}
}
