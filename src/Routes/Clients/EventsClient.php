<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\Event;

class EventsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(
    string $device_id = null,
    string $event_id = null,
    string $event_type = null
  ): Event {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($event_id !== null) {
      $request_payload["event_id"] = $event_id;
    }
    if ($event_type !== null) {
      $request_payload["event_type"] = $event_type;
    }

    $res = $this->seam->request(
      "POST",
      "/events/get",
      json: (object) $request_payload,
      inner_object: "event"
    );

    return Event::from_json($res);
  }

  public function list(
    string $access_code_id = null,
    array $access_code_ids = null,
    string $acs_system_id = null,
    array $acs_system_ids = null,
    array $between = null,
    string $connect_webview_id = null,
    string $connected_account_id = null,
    string $device_id = null,
    array $device_ids = null,
    string $event_type = null,
    array $event_types = null,
    float $limit = null,
    string $since = null,
    float $unstable_offset = null
  ): array {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($access_code_ids !== null) {
      $request_payload["access_code_ids"] = $access_code_ids;
    }
    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }
    if ($acs_system_ids !== null) {
      $request_payload["acs_system_ids"] = $acs_system_ids;
    }
    if ($between !== null) {
      $request_payload["between"] = $between;
    }
    if ($connect_webview_id !== null) {
      $request_payload["connect_webview_id"] = $connect_webview_id;
    }
    if ($connected_account_id !== null) {
      $request_payload["connected_account_id"] = $connected_account_id;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($device_ids !== null) {
      $request_payload["device_ids"] = $device_ids;
    }
    if ($event_type !== null) {
      $request_payload["event_type"] = $event_type;
    }
    if ($event_types !== null) {
      $request_payload["event_types"] = $event_types;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }
    if ($since !== null) {
      $request_payload["since"] = $since;
    }
    if ($unstable_offset !== null) {
      $request_payload["unstable_offset"] = $unstable_offset;
    }

    $res = $this->seam->request(
      "POST",
      "/events/list",
      json: (object) $request_payload,
      inner_object: "events"
    );

    return array_map(fn($r) => Event::from_json($r), $res);
  }
}
