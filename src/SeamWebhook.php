<?php

namespace Seam;

use Seam\Resources\Event;
use Svix\Exception\WebhookVerificationException;
use Svix\Webhook;

/**
 * Verifies and parses incoming Seam webhook events.
 *
 * Named SeamWebhook rather than Webhook to leave that name to the webhook
 * resource returned by the API.
 *
 * Verification failures raise Svix\Exception\WebhookVerificationException.
 */
class SeamWebhook
{
    private Webhook $webhook;

    public function __construct(string $secret)
    {
        $this->webhook = new Webhook($secret);
    }

    /**
     * Verifies an incoming webhook request and returns the event it carries.
     *
     * @param string $payload The raw HTTP request body.
     * @param array<string, string> $headers The HTTP request headers.
     *
     * @throws \Svix\Exception\WebhookVerificationException When the signature does not match.
     */
    public function verify(string $payload, array $headers): Event
    {
        $normalized_headers = [];
        foreach ($headers as $name => $value) {
            $normalized_headers[strtolower($name)] = $value;
        }

        $this->webhook->verify($payload, $normalized_headers);

        $event = Event::from_json(json_decode($payload));

        if ($event === null) {
            throw new WebhookVerificationException(
                "The verified webhook payload did not contain an event",
            );
        }

        return $event;
    }
}
