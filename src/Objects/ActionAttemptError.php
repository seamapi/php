<?php

namespace Seam\Objects;

class ActionAttemptError
{
    public static function from_json(mixed $json): ActionAttemptError|null
    {
        if (!$json) {
            return null;
        }
        return new self(message: $json->message, type: $json->type);
    }

    public function __construct(public string $message, public string $type)
    {
    }
}
