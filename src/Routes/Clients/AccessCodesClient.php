<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\AccessCode;

class AccessCodesClient
{
  private SeamClient $seam;
  public AccessCodesSimulateClient $simulate;
  public AccessCodesUnmanagedClient $unmanaged;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->simulate = new AccessCodesSimulateClient($seam);
    $this->unmanaged = new AccessCodesUnmanagedClient($seam);
  }

  public function create(
    string $device_id,
    bool $allow_external_modification = null,
    bool $attempt_for_offline_device = null,
    string $code = null,
    string $common_code_key = null,
    string $ends_at = null,
    bool $is_external_modification_allowed = null,
    bool $is_offline_access_code = null,
    bool $is_one_time_use = null,
    string $max_time_rounding = null,
    string $name = null,
    bool $prefer_native_scheduling = null,
    float $preferred_code_length = null,
    string $starts_at = null,
    bool $sync = null,
    bool $use_backup_access_code_pool = null,
    bool $use_offline_access_code = null
  ): AccessCode {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($allow_external_modification !== null) {
      $request_payload["allow_external_modification"] = $allow_external_modification;
    }
    if ($attempt_for_offline_device !== null) {
      $request_payload["attempt_for_offline_device"] = $attempt_for_offline_device;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($common_code_key !== null) {
      $request_payload["common_code_key"] = $common_code_key;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_external_modification_allowed !== null) {
      $request_payload["is_external_modification_allowed"] = $is_external_modification_allowed;
    }
    if ($is_offline_access_code !== null) {
      $request_payload["is_offline_access_code"] = $is_offline_access_code;
    }
    if ($is_one_time_use !== null) {
      $request_payload["is_one_time_use"] = $is_one_time_use;
    }
    if ($max_time_rounding !== null) {
      $request_payload["max_time_rounding"] = $max_time_rounding;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($prefer_native_scheduling !== null) {
      $request_payload["prefer_native_scheduling"] = $prefer_native_scheduling;
    }
    if ($preferred_code_length !== null) {
      $request_payload["preferred_code_length"] = $preferred_code_length;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }
    if ($use_backup_access_code_pool !== null) {
      $request_payload["use_backup_access_code_pool"] = $use_backup_access_code_pool;
    }
    if ($use_offline_access_code !== null) {
      $request_payload["use_offline_access_code"] = $use_offline_access_code;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/create",
      json: (object) $request_payload,
      inner_object: "access_code"
    );

    return AccessCode::from_json($res);
  }

  public function create_multiple(
    array $device_ids,
    bool $allow_external_modification = null,
    bool $attempt_for_offline_device = null,
    string $behavior_when_code_cannot_be_shared = null,
    string $code = null,
    string $ends_at = null,
    bool $is_external_modification_allowed = null,
    bool $is_offline_access_code = null,
    bool $is_one_time_use = null,
    string $max_time_rounding = null,
    string $name = null,
    bool $prefer_native_scheduling = null,
    float $preferred_code_length = null,
    string $starts_at = null,
    bool $use_backup_access_code_pool = null,
    bool $use_offline_access_code = null
  ): array {
    $request_payload = [];

    if ($device_ids !== null) {
      $request_payload["device_ids"] = $device_ids;
    }
    if ($allow_external_modification !== null) {
      $request_payload["allow_external_modification"] = $allow_external_modification;
    }
    if ($attempt_for_offline_device !== null) {
      $request_payload["attempt_for_offline_device"] = $attempt_for_offline_device;
    }
    if ($behavior_when_code_cannot_be_shared !== null) {
      $request_payload["behavior_when_code_cannot_be_shared"] = $behavior_when_code_cannot_be_shared;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_external_modification_allowed !== null) {
      $request_payload["is_external_modification_allowed"] = $is_external_modification_allowed;
    }
    if ($is_offline_access_code !== null) {
      $request_payload["is_offline_access_code"] = $is_offline_access_code;
    }
    if ($is_one_time_use !== null) {
      $request_payload["is_one_time_use"] = $is_one_time_use;
    }
    if ($max_time_rounding !== null) {
      $request_payload["max_time_rounding"] = $max_time_rounding;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($prefer_native_scheduling !== null) {
      $request_payload["prefer_native_scheduling"] = $prefer_native_scheduling;
    }
    if ($preferred_code_length !== null) {
      $request_payload["preferred_code_length"] = $preferred_code_length;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }
    if ($use_backup_access_code_pool !== null) {
      $request_payload["use_backup_access_code_pool"] = $use_backup_access_code_pool;
    }
    if ($use_offline_access_code !== null) {
      $request_payload["use_offline_access_code"] = $use_offline_access_code;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/create_multiple",
      json: (object) $request_payload,
      inner_object: "access_codes"
    );

    return array_map(fn($r) => AccessCode::from_json($r), $res);
  }

  public function delete(
    string $access_code_id,
    string $device_id = null,
    bool $sync = null
  ): void {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $this->seam->request(
      "POST",
      "/access_codes/delete",
      json: (object) $request_payload
    );
  }

  public function generate_code(string $device_id): AccessCode
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/generate_code",
      json: (object) $request_payload,
      inner_object: "generated_code"
    );

    return AccessCode::from_json($res);
  }

  public function get(
    string $access_code_id = null,
    string $code = null,
    string $device_id = null
  ): AccessCode {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/get",
      json: (object) $request_payload,
      inner_object: "access_code"
    );

    return AccessCode::from_json($res);
  }

  public function list(
    array $access_code_ids = null,
    string $device_id = null,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($access_code_ids !== null) {
      $request_payload["access_code_ids"] = $access_code_ids;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/list",
      json: (object) $request_payload,
      inner_object: "access_codes"
    );

    return array_map(fn($r) => AccessCode::from_json($r), $res);
  }

  public function pull_backup_access_code(string $access_code_id): AccessCode
  {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/pull_backup_access_code",
      json: (object) $request_payload,
      inner_object: "access_code"
    );

    return AccessCode::from_json($res);
  }

  public function update(
    string $access_code_id,
    bool $allow_external_modification = null,
    bool $attempt_for_offline_device = null,
    string $code = null,
    string $device_id = null,
    string $ends_at = null,
    bool $is_external_modification_allowed = null,
    bool $is_managed = null,
    bool $is_offline_access_code = null,
    bool $is_one_time_use = null,
    string $max_time_rounding = null,
    string $name = null,
    bool $prefer_native_scheduling = null,
    float $preferred_code_length = null,
    string $starts_at = null,
    bool $sync = null,
    string $type = null,
    bool $use_backup_access_code_pool = null,
    bool $use_offline_access_code = null
  ): void {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($allow_external_modification !== null) {
      $request_payload["allow_external_modification"] = $allow_external_modification;
    }
    if ($attempt_for_offline_device !== null) {
      $request_payload["attempt_for_offline_device"] = $attempt_for_offline_device;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_external_modification_allowed !== null) {
      $request_payload["is_external_modification_allowed"] = $is_external_modification_allowed;
    }
    if ($is_managed !== null) {
      $request_payload["is_managed"] = $is_managed;
    }
    if ($is_offline_access_code !== null) {
      $request_payload["is_offline_access_code"] = $is_offline_access_code;
    }
    if ($is_one_time_use !== null) {
      $request_payload["is_one_time_use"] = $is_one_time_use;
    }
    if ($max_time_rounding !== null) {
      $request_payload["max_time_rounding"] = $max_time_rounding;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($prefer_native_scheduling !== null) {
      $request_payload["prefer_native_scheduling"] = $prefer_native_scheduling;
    }
    if ($preferred_code_length !== null) {
      $request_payload["preferred_code_length"] = $preferred_code_length;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }
    if ($type !== null) {
      $request_payload["type"] = $type;
    }
    if ($use_backup_access_code_pool !== null) {
      $request_payload["use_backup_access_code_pool"] = $use_backup_access_code_pool;
    }
    if ($use_offline_access_code !== null) {
      $request_payload["use_offline_access_code"] = $use_offline_access_code;
    }

    $this->seam->request(
      "POST",
      "/access_codes/update",
      json: (object) $request_payload
    );
  }

  public function update_multiple(
    string $common_code_key,
    string $ends_at = null,
    string $name = null,
    string $starts_at = null
  ): void {
    $request_payload = [];

    if ($common_code_key !== null) {
      $request_payload["common_code_key"] = $common_code_key;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }

    $this->seam->request(
      "POST",
      "/access_codes/update_multiple",
      json: (object) $request_payload
    );
  }
}
