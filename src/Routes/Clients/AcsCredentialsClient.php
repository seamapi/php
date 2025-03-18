<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsCredential;
use Seam\Routes\Objects\AcsEntrance;

class AcsCredentialsClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function assign(string $acs_credential_id, string $acs_user_id): void
  {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/credentials/assign",
      json: (object) $request_payload
    );
  }

  public function create(
    string $access_method,
    string $acs_user_id,
    array $allowed_acs_entrance_ids = null,
    mixed $assa_abloy_vostio_metadata = null,
    string $code = null,
    string $credential_manager_acs_system_id = null,
    string $ends_at = null,
    bool $is_multi_phone_sync_credential = null,
    mixed $salto_space_metadata = null,
    string $starts_at = null,
    mixed $visionline_metadata = null
  ): AcsCredential {
    $request_payload = [];

    if ($access_method !== null) {
      $request_payload["access_method"] = $access_method;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($allowed_acs_entrance_ids !== null) {
      $request_payload["allowed_acs_entrance_ids"] = $allowed_acs_entrance_ids;
    }
    if ($assa_abloy_vostio_metadata !== null) {
      $request_payload["assa_abloy_vostio_metadata"] = $assa_abloy_vostio_metadata;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($credential_manager_acs_system_id !== null) {
      $request_payload["credential_manager_acs_system_id"] = $credential_manager_acs_system_id;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_multi_phone_sync_credential !== null) {
      $request_payload["is_multi_phone_sync_credential"] = $is_multi_phone_sync_credential;
    }
    if ($salto_space_metadata !== null) {
      $request_payload["salto_space_metadata"] = $salto_space_metadata;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }
    if ($visionline_metadata !== null) {
      $request_payload["visionline_metadata"] = $visionline_metadata;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/create",
      json: (object) $request_payload,
      inner_object: "acs_credential"
    );

    return AcsCredential::from_json($res);
  }

  public function create_offline_code(
    string $acs_user_id,
    string $allowed_acs_entrance_id,
    string $ends_at = null,
    bool $is_one_time_use = null,
    string $starts_at = null
  ): AcsCredential {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($allowed_acs_entrance_id !== null) {
      $request_payload["allowed_acs_entrance_id"] = $allowed_acs_entrance_id;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }
    if ($is_one_time_use !== null) {
      $request_payload["is_one_time_use"] = $is_one_time_use;
    }
    if ($starts_at !== null) {
      $request_payload["starts_at"] = $starts_at;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/create_offline_code",
      json: (object) $request_payload,
      inner_object: "acs_credential"
    );

    return AcsCredential::from_json($res);
  }

  public function delete(string $acs_credential_id): void
  {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }

    $this->seam->request(
      "POST",
      "/acs/credentials/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $acs_credential_id): AcsCredential
  {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/get",
      json: (object) $request_payload,
      inner_object: "acs_credential"
    );

    return AcsCredential::from_json($res);
  }

  public function list(
    string $acs_user_id = null,
    string $acs_system_id = null,
    string $user_identity_id = null,
    string $created_before = null,
    bool $is_multi_phone_sync_credential = null,
    float $limit = null
  ): array {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($created_before !== null) {
      $request_payload["created_before"] = $created_before;
    }
    if ($is_multi_phone_sync_credential !== null) {
      $request_payload["is_multi_phone_sync_credential"] = $is_multi_phone_sync_credential;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/list",
      json: (object) $request_payload,
      inner_object: "acs_credentials"
    );

    return array_map(fn($r) => AcsCredential::from_json($r), $res);
  }

  public function list_accessible_entrances(string $acs_credential_id): array
  {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/list_accessible_entrances",
      json: (object) $request_payload,
      inner_object: "acs_entrances"
    );

    return array_map(fn($r) => AcsEntrance::from_json($r), $res);
  }

  public function unassign(
    string $acs_credential_id,
    string $acs_user_id
  ): void {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/credentials/unassign",
      json: (object) $request_payload
    );
  }

  public function update(
    string $acs_credential_id,
    string $code = null,
    string $ends_at = null
  ): void {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }
    if ($code !== null) {
      $request_payload["code"] = $code;
    }
    if ($ends_at !== null) {
      $request_payload["ends_at"] = $ends_at;
    }

    $this->seam->request(
      "POST",
      "/acs/credentials/update",
      json: (object) $request_payload
    );
  }
}
