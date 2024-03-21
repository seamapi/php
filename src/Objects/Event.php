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
            event_id: $json->event_id,
            device_id: $json->device_id ?? null,
            event_type: $json->event_type,
            workspace_id: $json->workspace_id,
            created_at: $json->created_at,
            occurred_at: $json->occurred_at,
        );
    }
  

    
    public function __construct(
        public string $event_id,
        public string | null $device_id,
        public string $event_type,
        public string $workspace_id,
        public string $created_at,
        public string $occurred_at,
    ) {
    }
  
}
