<?php

namespace Seam\Objects;

class SeamError
{
    public static function from_json(mixed $json): SeamError|null
    {
        return null;
    }

    public string $error_type;
    public string $message;
}
