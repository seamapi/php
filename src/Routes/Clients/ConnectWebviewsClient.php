<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ConnectWebview;

class ConnectWebviewsClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create(
    array $accepted_providers = null,
    bool $automatically_manage_new_devices = null,
    mixed $custom_metadata = null,
    string $custom_redirect_failure_url = null,
    string $custom_redirect_url = null,
    string $device_selection_mode = null,
    string $provider_category = null,
    bool $wait_for_device_creation = null
  ): ConnectWebview {
    $request_payload = [];

    if ($accepted_providers !== null) {
      $request_payload["accepted_providers"] = $accepted_providers;
    }
    if ($automatically_manage_new_devices !== null) {
      $request_payload["automatically_manage_new_devices"] = $automatically_manage_new_devices;
    }
    if ($custom_metadata !== null) {
      $request_payload["custom_metadata"] = $custom_metadata;
    }
    if ($custom_redirect_failure_url !== null) {
      $request_payload["custom_redirect_failure_url"] = $custom_redirect_failure_url;
    }
    if ($custom_redirect_url !== null) {
      $request_payload["custom_redirect_url"] = $custom_redirect_url;
    }
    if ($device_selection_mode !== null) {
      $request_payload["device_selection_mode"] = $device_selection_mode;
    }
    if ($provider_category !== null) {
      $request_payload["provider_category"] = $provider_category;
    }
    if ($wait_for_device_creation !== null) {
      $request_payload["wait_for_device_creation"] = $wait_for_device_creation;
    }

    $res = $this->seam->request(
      "POST",
      "/connect_webviews/create",
      json: (object) $request_payload,
      inner_object: "connect_webview"
    );

    return ConnectWebview::from_json($res);
  }

  public function delete(string $connect_webview_id): void
  {
    $request_payload = [];

    if ($connect_webview_id !== null) {
      $request_payload["connect_webview_id"] = $connect_webview_id;
    }

    $this->seam->request(
      "POST",
      "/connect_webviews/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $connect_webview_id): ConnectWebview
  {
    $request_payload = [];

    if ($connect_webview_id !== null) {
      $request_payload["connect_webview_id"] = $connect_webview_id;
    }

    $res = $this->seam->request(
      "POST",
      "/connect_webviews/get",
      json: (object) $request_payload,
      inner_object: "connect_webview"
    );

    return ConnectWebview::from_json($res);
  }

  public function list(
    mixed $custom_metadata_has = null,
    float $limit = null,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($custom_metadata_has !== null) {
      $request_payload["custom_metadata_has"] = $custom_metadata_has;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/connect_webviews/list",
      json: (object) $request_payload,
      inner_object: "connect_webviews"
    );

    return array_map(fn($r) => ConnectWebview::from_json($r), $res);
  }
}
