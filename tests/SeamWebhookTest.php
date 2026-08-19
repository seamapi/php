<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\InvalidWebhookPayloadError;
use Seam\SeamException;
use Seam\SeamWebhook;
use Svix\Exception\WebhookVerificationException;
use Svix\Webhook;

final class SeamWebhookTest extends TestCase
{
    private const SECRET = "whsec_MfKQ9r8GKYqrTwjUPD8ILPZIo2LaLaSw";

    private function payload(): string
    {
        return json_encode([
            "event_id" => "8d7e0b26-5e6c-4a1f-9b3d-1b0f0e5a9c11",
            "event_type" => "device.connected",
            "workspace_id" => "398d80b7-3f96-47c2-b85a-6f8ba21d07be",
            "device_id" => "054765c8-a2fc-4599-b486-14c19f462c45",
            "created_at" => "2024-01-01T00:00:00.000Z",
            "occurred_at" => "2024-01-01T00:00:00.000Z",
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function signed_headers(string $payload, ?int $at = null): array
    {
        $id = "msg_test";
        $timestamp = (string) ($at ?? time());

        $signature = (new Webhook(self::SECRET))->sign(
            $id,
            $timestamp,
            $payload,
        );

        return [
            "svix-id" => $id,
            "svix-timestamp" => $timestamp,
            "svix-signature" => $signature,
        ];
    }

    public function testVerifyReturnsTheEvent(): void
    {
        $payload = $this->payload();

        $event = (new SeamWebhook(self::SECRET))->verify(
            $payload,
            $this->signed_headers($payload),
        );

        $this->assertSame("device.connected", $event->event_type);
        $this->assertSame(
            "8d7e0b26-5e6c-4a1f-9b3d-1b0f0e5a9c11",
            $event->event_id,
        );
    }

    public function testVerifyAcceptsHeadersInAnyCase(): void
    {
        $payload = $this->payload();

        $headers = [];
        foreach ($this->signed_headers($payload) as $name => $value) {
            $headers[strtoupper($name)] = $value;
        }

        $event = (new SeamWebhook(self::SECRET))->verify($payload, $headers);

        $this->assertSame("device.connected", $event->event_type);
    }

    public function testVerifyRejectsATamperedPayload(): void
    {
        $payload = $this->payload();
        $headers = $this->signed_headers($payload);

        $this->expectException(WebhookVerificationException::class);

        (new SeamWebhook(self::SECRET))->verify(
            str_replace("device.connected", "device.disconnected", $payload),
            $headers,
        );
    }

    public function testVerifyRejectsTheWrongSecret(): void
    {
        $payload = $this->payload();
        $headers = $this->signed_headers($payload);

        $this->expectException(WebhookVerificationException::class);

        (new SeamWebhook(
            "whsec_AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=",
        ))->verify($payload, $headers);
    }

    public function testVerifyRejectsAnExpiredTimestamp(): void
    {
        $payload = $this->payload();

        $this->expectException(WebhookVerificationException::class);

        (new SeamWebhook(self::SECRET))->verify(
            $payload,
            $this->signed_headers($payload, time() - 3600),
        );
    }

    /**
     * @dataProvider missingHeaders
     */
    public function testVerifyRejectsAMissingHeader(string $missing): void
    {
        $payload = $this->payload();
        $headers = $this->signed_headers($payload);
        unset($headers[$missing]);

        $this->expectException(WebhookVerificationException::class);

        (new SeamWebhook(self::SECRET))->verify($payload, $headers);
    }

    public static function missingHeaders(): array
    {
        return [
            "svix-id" => ["svix-id"],
            "svix-timestamp" => ["svix-timestamp"],
            "svix-signature" => ["svix-signature"],
        ];
    }

    /**
     * @dataProvider unreadablePayloads
     */
    public function testVerifyDistinguishesAnUnreadablePayload(
        string $payload,
    ): void {
        $headers = $this->signed_headers($payload);

        try {
            (new SeamWebhook(self::SECRET))->verify($payload, $headers);
            $this->fail("Expected InvalidWebhookPayloadError");
        } catch (InvalidWebhookPayloadError $error) {
            $this->assertInstanceOf(SeamException::class, $error);
            $this->assertNotInstanceOf(
                WebhookVerificationException::class,
                $error,
            );
        }
    }

    public static function unreadablePayloads(): array
    {
        return [
            "malformed json" => ["{not json"],
            "json that is not an object" => ["[1, 2]"],
            "json null" => ["null"],
            "empty body" => [""],
            "object that is not an event" => ['{"hello":"world"}'],
        ];
    }
}
