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
            event_id: $json->event_id ?? null,
            device_id: $json->device_id ?? null,
            event_type: $json->event_type ?? null,
            workspace_id: $json->workspace_id ?? null,
            created_at: $json->created_at ?? null,
            occurred_at: $json->occurred_at ?? null
        );
    }

    public function __construct(
        public string|null $event_id,
        public string|null $device_id,
        public string|null $event_type,
        public string|null $workspace_id,
        public string|null $created_at,
        public string|null $occurred_at
    ) {
    }
}
