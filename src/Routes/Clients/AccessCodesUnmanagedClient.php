<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\UnmanagedAccessCode;

class AccessCodesUnmanagedClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function convert_to_managed(
    string $access_code_id,
    bool $allow_external_modification = null,
    bool $force = null,
    bool $is_external_modification_allowed = null,
    bool $sync = null
  ): void {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($allow_external_modification !== null) {
      $request_payload["allow_external_modification"] = $allow_external_modification;
    }
    if ($force !== null) {
      $request_payload["force"] = $force;
    }
    if ($is_external_modification_allowed !== null) {
      $request_payload["is_external_modification_allowed"] = $is_external_modification_allowed;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $this->seam->request(
      "POST",
      "/access_codes/unmanaged/convert_to_managed",
      json: (object) $request_payload
    );
  }

  public function delete(string $access_code_id, bool $sync = null): void
  {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $this->seam->request(
      "POST",
      "/access_codes/unmanaged/delete",
      json: (object) $request_payload
    );
  }

  public function get(
    string $access_code_id = null,
    string $code = null,
    string $device_id = null
  ): UnmanagedAccessCode {
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
      "/access_codes/unmanaged/get",
      json: (object) $request_payload,
      inner_object: "access_code"
    );

    return UnmanagedAccessCode::from_json($res);
  }

  public function list(
    string $device_id,
    string $user_identifier_key = null
  ): array {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/access_codes/unmanaged/list",
      json: (object) $request_payload,
      inner_object: "access_codes"
    );

    return array_map(fn($r) => UnmanagedAccessCode::from_json($r), $res);
  }

  public function update(
    string $access_code_id,
    bool $is_managed,
    bool $allow_external_modification = null,
    bool $force = null,
    bool $is_external_modification_allowed = null
  ): void {
    $request_payload = [];

    if ($access_code_id !== null) {
      $request_payload["access_code_id"] = $access_code_id;
    }
    if ($is_managed !== null) {
      $request_payload["is_managed"] = $is_managed;
    }
    if ($allow_external_modification !== null) {
      $request_payload["allow_external_modification"] = $allow_external_modification;
    }
    if ($force !== null) {
      $request_payload["force"] = $force;
    }
    if ($is_external_modification_allowed !== null) {
      $request_payload["is_external_modification_allowed"] = $is_external_modification_allowed;
    }

    $this->seam->request(
      "POST",
      "/access_codes/unmanaged/update",
      json: (object) $request_payload
    );
  }
}
