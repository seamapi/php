<?php

namespace Seam\Objects;

class Event
{
    public static function from_json(mixed $json): Event|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            device_id: $json->device_id ?? null,
            event_id: $json->event_id,
            event_type: $json->event_type,
            occurred_at: $json->occurred_at,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $created_at,
        public string|null $device_id,
        public string $event_id,
        public string $event_type,
        public string $occurred_at,
        public string $workspace_id
    ) {
    }
}
