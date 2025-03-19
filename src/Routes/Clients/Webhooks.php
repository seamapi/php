<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Webhook;

class Webhooks
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create(string $url, ?array $event_types = null): Webhook
    {
        $request_payload = [];

        if ($url !== null) {
            $request_payload["url"] = $url;
        }
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }

        $res = $this->seam->client->post("/webhooks/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Webhook::from_json($json->webhook);
    }

    public function delete(string $webhook_id): void
    {
        $request_payload = [];

        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }

        $this->seam->client->post("/webhooks/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $webhook_id): Webhook
    {
        $request_payload = [];

        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }

        $res = $this->seam->client->post("/webhooks/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Webhook::from_json($json->webhook);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->client->post("/webhooks/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Webhook::from_json($r), $json->webhooks);
    }

    public function update(string $webhook_id, array $event_types): void
    {
        $request_payload = [];

        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }

        $this->seam->client->post("/webhooks/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
