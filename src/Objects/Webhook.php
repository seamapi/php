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
            url: $json->url,
            webhook_id: $json->webhook_id,
            event_types: $json->event_types ?? null,
            secret: $json->secret ?? null
        );
    }

    public function __construct(
        public string $url,
        public string $webhook_id,
        public array|null $event_types,
        public string|null $secret
    ) {
    }
}
