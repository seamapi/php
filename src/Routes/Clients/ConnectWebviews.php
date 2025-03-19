<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ConnectWebview;

class ConnectWebviews
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        ?string $device_selection_mode = null,
        ?string $custom_redirect_url = null,
        ?string $custom_redirect_failure_url = null,
        ?array $accepted_providers = null,
        ?string $provider_category = null,
        mixed $custom_metadata = null,
        ?bool $automatically_manage_new_devices = null,
        ?bool $wait_for_device_creation = null
    ): ConnectWebview {
        $request_payload = [];

        if ($device_selection_mode !== null) {
            $request_payload["device_selection_mode"] = $device_selection_mode;
        }
        if ($custom_redirect_url !== null) {
            $request_payload["custom_redirect_url"] = $custom_redirect_url;
        }
        if ($custom_redirect_failure_url !== null) {
            $request_payload[
                "custom_redirect_failure_url"
            ] = $custom_redirect_failure_url;
        }
        if ($accepted_providers !== null) {
            $request_payload["accepted_providers"] = $accepted_providers;
        }
        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($wait_for_device_creation !== null) {
            $request_payload[
                "wait_for_device_creation"
            ] = $wait_for_device_creation;
        }

        $res = $this->seam->client->post("/connect_webviews/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return ConnectWebview::from_json($json->connect_webview);
    }

    public function delete(string $connect_webview_id): void
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $this->seam->client->post("/connect_webviews/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $connect_webview_id): ConnectWebview
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $res = $this->seam->client->post("/connect_webviews/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return ConnectWebview::from_json($json->connect_webview);
    }

    public function list(
        ?string $user_identifier_key = null,
        mixed $custom_metadata_has = null,
        ?float $limit = null
    ): array {
        $request_payload = [];

        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }

        $res = $this->seam->client->post("/connect_webviews/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => ConnectWebview::from_json($r),
            $json->connect_webviews
        );
    }
}
