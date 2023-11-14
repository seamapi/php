<?php

namespace Seam\Objects;

class Webhook
{
    public static function from_json(mixed $json): Webhook|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            webhook_id: $json->webhook_id ?? null,
            url: $json->url ?? null,
            event_types: $json->event_types ?? null,
            secret: $json->secret ?? null
        );
    }

    public function __construct(
        public string|null $webhook_id,
        public string|null $url,
        public array|null $event_types,
        public string|null $secret
    ) {
    }
}
