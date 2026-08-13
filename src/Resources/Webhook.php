<?php

namespace Seam\Resources {
    /**
     * Represents a [webhook](https://docs.seam.co/developer-tools/webhooks) that enables you to receive notifications of events. When you create a webhook, specify the endpoint URL at which you want to receive events and the set of event types that you want to receive.
     */
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
            /**
             * Types of events that the [webhook](https://docs.seam.co/developer-tools/webhooks) should receive.
             */
            public array|null $event_types,
            /**
             * Secret associated with the [webhook](https://docs.seam.co/developer-tools/webhooks).
             */
            public string|null $secret,
            /**
             * URL for the [webhook](https://docs.seam.co/developer-tools/webhooks).
             */
            public string|null $url,
            /**
             * ID of the webhook.
             */
            public string|null $webhook_id,
        ) {}
    }
}
