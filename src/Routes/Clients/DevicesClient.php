<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Device;
use Seam\Routes\Objects\DeviceProvider;

class DevicesClient
{
  private Seam $seam;
  public DevicesSimulateClient $simulate;
  public DevicesUnmanagedClient $unmanaged;
  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
    $this->simulate = new DevicesSimulateClient($seam);
    $this->unmanaged = new DevicesUnmanagedClient($seam);
  }

  public function delete(string $device_id): void
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $this->seam->request(
      "POST",
      "/devices/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $device_id = null, string $name = null): Device
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }

    $res = $this->seam->request(
      "POST",
      "/devices/get",
      json: (object) $request_payload,
      inner_object: "device"
    );

    return Device::from_json($res);
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
      "/devices/list",
      json: (object) $request_payload,
      inner_object: "devices"
    );

    return array_map(fn($r) => Device::from_json($r), $res);
  }

  public function list_device_providers(
    string $provider_category = null
  ): array {
    $request_payload = [];

    if ($provider_category !== null) {
      $request_payload["provider_category"] = $provider_category;
    }

    $res = $this->seam->request(
      "POST",
      "/devices/list_device_providers",
      json: (object) $request_payload,
      inner_object: "device_providers"
    );

    return array_map(fn($r) => DeviceProvider::from_json($r), $res);
  }

  public function update(
    string $device_id,
    mixed $custom_metadata = null,
    bool $is_managed = null,
    string $name = null,
    mixed $properties = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($custom_metadata !== null) {
      $request_payload["custom_metadata"] = $custom_metadata;
    }
    if ($is_managed !== null) {
      $request_payload["is_managed"] = $is_managed;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($properties !== null) {
      $request_payload["properties"] = $properties;
    }

    $this->seam->request(
      "POST",
      "/devices/update",
      json: (object) $request_payload
    );
  }
}
