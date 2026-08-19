<?php

namespace Seam;

use Seam\Resources\Event;
use Svix\Webhook;

/**
 * Verifies and parses incoming Seam webhook events.
 *
 * Named SeamWebhook rather than Webhook to leave that name to the webhook
 * resource returned by the API.
 *
 * Verification failures raise Svix\Exception\WebhookVerificationException.
 * A verified payload that is not a readable event raises
 * InvalidWebhookPayloadError.
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
     * @throws InvalidWebhookPayloadError When the signature matches but the body is not a Seam event.
     */
    public function verify(string $payload, array $headers): Event
    {
        $normalized_headers = [];
        foreach ($headers as $name => $value) {
            $normalized_headers[strtolower((string) $name)] = $value;
        }

        $this->webhook->verify($payload, $normalized_headers);

        $decoded = json_decode($payload);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidWebhookPayloadError(
                "The verified webhook payload is not valid JSON: " .
                    json_last_error_msg(),
            );
        }

        $event = Event::from_json($decoded);

        if ($event === null || $event->event_id === null) {
            throw new InvalidWebhookPayloadError(
                "The verified webhook payload did not contain an event",
            );
        }

        return $event;
    }
}
