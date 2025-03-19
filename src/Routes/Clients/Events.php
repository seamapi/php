<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Event;

class Events
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function get(
        ?string $event_id = null,
        ?string $event_type = null,
        ?string $device_id = null
    ): Event {
        $request_payload = [];

        if ($event_id !== null) {
            $request_payload["event_id"] = $event_id;
        }
        if ($event_type !== null) {
            $request_payload["event_type"] = $event_type;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->client->post("/events/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Event::from_json($json->event);
    }

    public function list(
        ?float $unstable_offset = null,
        ?string $since = null,
        ?array $between = null,
        ?string $device_id = null,
        ?array $device_ids = null,
        ?string $acs_system_id = null,
        ?array $acs_system_ids = null,
        ?string $access_code_id = null,
        ?array $access_code_ids = null,
        ?string $event_type = null,
        ?array $event_types = null,
        ?string $connected_account_id = null,
        ?string $connect_webview_id = null,
        ?float $limit = null,
        ?array $event_ids = null
    ): array {
        $request_payload = [];

        if ($unstable_offset !== null) {
            $request_payload["unstable_offset"] = $unstable_offset;
        }
        if ($since !== null) {
            $request_payload["since"] = $since;
        }
        if ($between !== null) {
            $request_payload["between"] = $between;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($event_type !== null) {
            $request_payload["event_type"] = $event_type;
        }
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($event_ids !== null) {
            $request_payload["event_ids"] = $event_ids;
        }

        $res = $this->seam->client->post("/events/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Event::from_json($r), $json->events);
    }
}
