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
            url: $json->url ?? null,
            webhook_id: $json->webhook_id ?? null,
        );
    }

    public function __construct(
        public array|null $event_types,
        public string|null $secret,
        public string|null $url,
        public string|null $webhook_id,
    ) {}
}
