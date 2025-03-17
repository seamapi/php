<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\UnmanagedDevice;

class DevicesUnmanagedClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(
    string $device_id = null,
    string $name = null
  ): UnmanagedDevice {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $res = $this->seam->request(
      "POST",
      "/devices/unmanaged/get",
      json: (object) $request_payload,
      inner_object: "device"
    );

    return UnmanagedDevice::from_json($res);
  }

  public function list(
    string $connect_webview_id = null,
    string $connected_account_id = null,
    array $connected_account_ids = null,
    string $created_before = null,
    mixed $custom_metadata_has = null,
    array $device_ids = null,
    string $device_type = null,
    array $device_types = null,
    array $exclude_if = null,
    array $include_if = null,
    float $limit = null,
    string $manufacturer = null,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($connect_webview_id !== null) {
      $request_payload["connect_webview_id"] = $connect_webview_id;
    }
    if ($connected_account_id !== null) {
      $request_payload["connected_account_id"] = $connected_account_id;
    }
    if ($connected_account_ids !== null) {
      $request_payload["connected_account_ids"] = $connected_account_ids;
    }
    if ($created_before !== null) {
      $request_payload["created_before"] = $created_before;
    }
    if ($custom_metadata_has !== null) {
      $request_payload["custom_metadata_has"] = $custom_metadata_has;
    }
    if ($device_ids !== null) {
      $request_payload["device_ids"] = $device_ids;
    }
    if ($device_type !== null) {
      $request_payload["device_type"] = $device_type;
    }
    if ($device_types !== null) {
      $request_payload["device_types"] = $device_types;
    }
    if ($exclude_if !== null) {
      $request_payload["exclude_if"] = $exclude_if;
    }
    if ($include_if !== null) {
      $request_payload["include_if"] = $include_if;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }
    if ($manufacturer !== null) {
      $request_payload["manufacturer"] = $manufacturer;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/devices/unmanaged/list",
      json: (object) $request_payload,
      inner_object: "devices"
    );

    return array_map(fn($r) => UnmanagedDevice::from_json($r), $res);
  }

  public function update(string $device_id, bool $is_managed): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($is_managed !== null) {
      $request_payload["is_managed"] = $is_managed;
    }

    $this->seam->request(
      "POST",
      "/devices/unmanaged/update",
      json: (object) $request_payload
    );
  }
}
