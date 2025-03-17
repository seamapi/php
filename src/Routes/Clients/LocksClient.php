<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\Device;
use Seam\Routes\Objects\ActionAttempt;

class LocksClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
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
      "/locks/get",
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
      "/locks/list",
      json: (object) $request_payload,
      inner_object: "devices"
    );

    return array_map(fn($r) => Device::from_json($r), $res);
  }

  public function lock_door(
    string $device_id,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/locks/lock_door",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }

  public function unlock_door(
    string $device_id,
    bool $sync = null,
    bool $wait_for_action_attempt = true
  ): ActionAttempt {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/locks/unlock_door",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    if (!$wait_for_action_attempt) {
      return ActionAttempt::from_json($res);
    }

    $action_attempt = $this->seam->action_attempts->poll_until_ready(
      $res->action_attempt_id
    );

    return $action_attempt;
  }
}
