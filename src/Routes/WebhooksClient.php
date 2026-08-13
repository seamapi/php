<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\Webhook;

class WebhooksClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Creates a new [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $url URL for the new webhook.
     * @param array $event_types Types of events that you want the new webhook to receive.
     * @return Webhook OK
     */
    public function create(string $url, ?array $event_types = null): Webhook
    {
        $request_payload = [];

        $request_payload["url"] = $url;
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }

        $res = Body::decode(
            $this->client->request("POST", "/webhooks/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return Webhook::from_json($res->webhook);
    }

    /**
     * Deletes a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $webhook_id ID of the webhook that you want to delete.
     * @return void OK
     */
    public function delete(string $webhook_id): void
    {
        $request_payload = [];

        $request_payload["webhook_id"] = $webhook_id;

        $this->client->request("POST", "/webhooks/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Gets a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $webhook_id ID of the webhook that you want to get.
     * @return Webhook OK
     */
    public function get(string $webhook_id): Webhook
    {
        $request_payload = [];

        $request_payload["webhook_id"] = $webhook_id;

        $res = Body::decode(
            $this->client->request("POST", "/webhooks/get", [
                "json" => (object) $request_payload,
            ]),
        );

        return Webhook::from_json($res->webhook);
    }

    /**
     * Returns a list of all [webhooks](https://docs.seam.co/developer-tools/webhooks).
     *
     * @return array OK
     */
    public function list(): array
    {
        $res = Body::decode($this->client->request("POST", "/webhooks/list"));

        return array_map(fn($r) => Webhook::from_json($r), $res->webhooks);
    }

    /**
     * Updates a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param array $event_types Types of events that you want the webhook to receive.
     * @param string $webhook_id ID of the webhook that you want to update.
     * @return void OK
     */
    public function update(array $event_types, string $webhook_id): void
    {
        $request_payload = [];

        $request_payload["event_types"] = $event_types;
        $request_payload["webhook_id"] = $webhook_id;

        $this->client->request("POST", "/webhooks/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
