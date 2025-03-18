<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Webhook;

class WebhooksClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create(string $url, array $event_types = null): Webhook
  {
    $request_payload = [];

    if ($url !== null) {
      $request_payload["url"] = $url;
    }
    if ($event_types !== null) {
      $request_payload["event_types"] = $event_types;
    }

    $res = $this->seam->request(
      "POST",
      "/webhooks/create",
      json: (object) $request_payload,
      inner_object: "webhook"
    );

    return Webhook::from_json($res);
  }

  public function delete(string $webhook_id): void
  {
    $request_payload = [];

    if ($webhook_id !== null) {
      $request_payload["webhook_id"] = $webhook_id;
    }

    $this->seam->request(
      "POST",
      "/webhooks/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $webhook_id): Webhook
  {
    $request_payload = [];

    if ($webhook_id !== null) {
      $request_payload["webhook_id"] = $webhook_id;
    }

    $res = $this->seam->request(
      "POST",
      "/webhooks/get",
      json: (object) $request_payload,
      inner_object: "webhook"
    );

    return Webhook::from_json($res);
  }

  public function list(): array
  {
    $request_payload = [];

    $res = $this->seam->request(
      "POST",
      "/webhooks/list",
      json: (object) $request_payload,
      inner_object: "webhooks"
    );

    return array_map(fn($r) => Webhook::from_json($r), $res);
  }

  public function update(array $event_types, string $webhook_id): void
  {
    $request_payload = [];

    if ($event_types !== null) {
      $request_payload["event_types"] = $event_types;
    }
    if ($webhook_id !== null) {
      $request_payload["webhook_id"] = $webhook_id;
    }

    $this->seam->request(
      "POST",
      "/webhooks/update",
      json: (object) $request_payload
    );
  }
}
