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
            event_types: $json->event_types ?? null,
            secret: $json->secret ?? null,
            url: $json->url,
            webhook_id: $json->webhook_id
        );
    }

    public function __construct(
        public array|null $event_types,
        public string|null $secret,
        public string $url,
        public string $webhook_id
    ) {
    }
}
