<?php

namespace Seam\Objects;

class Event
{
    public static function from_json(mixed $json): Event|null
    {
        if (!$json) {
            return null;
        }
        return new self(event_id: $json->event_id);
    }
    public function __construct(public string $event_id)
    {
    }
}
