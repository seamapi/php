<?php

namespace Seam\Objects;

class ActionAttemptError
{
    public static function from_json(mixed $json): ActionAttemptError|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            message: $json->message ?? null,
            type: $json->type ?? null,
        );
    }

    public function __construct(
        public string|null $message,
        public string|null $type,
    ) {}
}
