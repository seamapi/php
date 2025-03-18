<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\AcsUser;
use Seam\Routes\Objects\AcsEntrance;

class AcsUsersClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function add_to_access_group(
    string $acs_access_group_id,
    string $acs_user_id
  ): void {
    $request_payload = [];

    if ($acs_access_group_id !== null) {
      $request_payload["acs_access_group_id"] = $acs_access_group_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/add_to_access_group",
      json: (object) $request_payload
    );
  }

  public function create(
    string $acs_system_id,
    string $full_name,
    mixed $access_schedule = null,
    array $acs_access_group_ids = null,
    string $email = null,
    string $email_address = null,
    string $phone_number = null,
    string $user_identity_id = null
  ): AcsUser {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }
    if ($full_name !== null) {
      $request_payload["full_name"] = $full_name;
    }
    if ($access_schedule !== null) {
      $request_payload["access_schedule"] = $access_schedule;
    }
    if ($acs_access_group_ids !== null) {
      $request_payload["acs_access_group_ids"] = $acs_access_group_ids;
    }
    if ($email !== null) {
      $request_payload["email"] = $email;
    }
    if ($email_address !== null) {
      $request_payload["email_address"] = $email_address;
    }
    if ($phone_number !== null) {
      $request_payload["phone_number"] = $phone_number;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/users/create",
      json: (object) $request_payload,
      inner_object: "acs_user"
    );

    return AcsUser::from_json($res);
  }

  public function delete(string $acs_user_id): void
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $acs_user_id): AcsUser
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/users/get",
      json: (object) $request_payload,
      inner_object: "acs_user"
    );

    return AcsUser::from_json($res);
  }

  public function list(
    string $acs_system_id = null,
    string $created_before = null,
    float $limit = null,
    string $user_identity_email_address = null,
    string $user_identity_id = null,
    string $user_identity_phone_number = null
  ): array {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
    }
    if ($created_before !== null) {
      $request_payload["created_before"] = $created_before;
    }
    if ($limit !== null) {
      $request_payload["limit"] = $limit;
    }
    if ($user_identity_email_address !== null) {
      $request_payload["user_identity_email_address"] = $user_identity_email_address;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($user_identity_phone_number !== null) {
      $request_payload["user_identity_phone_number"] = $user_identity_phone_number;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/users/list",
      json: (object) $request_payload,
      inner_object: "acs_users"
    );

    return array_map(fn($r) => AcsUser::from_json($r), $res);
  }

  public function list_accessible_entrances(string $acs_user_id): array
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/users/list_accessible_entrances",
      json: (object) $request_payload,
      inner_object: "acs_entrances"
    );

    return array_map(fn($r) => AcsEntrance::from_json($r), $res);
  }

  public function remove_from_access_group(
    string $acs_access_group_id,
    string $acs_user_id
  ): void {
    $request_payload = [];

    if ($acs_access_group_id !== null) {
      $request_payload["acs_access_group_id"] = $acs_access_group_id;
    }
    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/remove_from_access_group",
      json: (object) $request_payload
    );
  }

  public function revoke_access_to_all_entrances(string $acs_user_id): void
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/revoke_access_to_all_entrances",
      json: (object) $request_payload
    );
  }

  public function suspend(string $acs_user_id): void
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/suspend",
      json: (object) $request_payload
    );
  }

  public function unsuspend(string $acs_user_id): void
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/acs/users/unsuspend",
      json: (object) $request_payload
    );
  }

  public function update(
    string $acs_user_id,
    mixed $access_schedule = null,
    string $email = null,
    string $email_address = null,
    string $full_name = null,
    string $hid_acs_system_id = null,
    string $phone_number = null
  ): void {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($access_schedule !== null) {
      $request_payload["access_schedule"] = $access_schedule;
    }
    if ($email !== null) {
      $request_payload["email"] = $email;
    }
    if ($email_address !== null) {
      $request_payload["email_address"] = $email_address;
    }
    if ($full_name !== null) {
      $request_payload["full_name"] = $full_name;
    }
    if ($hid_acs_system_id !== null) {
      $request_payload["hid_acs_system_id"] = $hid_acs_system_id;
    }
    if ($phone_number !== null) {
      $request_payload["phone_number"] = $phone_number;
    }

    $this->seam->request(
      "POST",
      "/acs/users/update",
      json: (object) $request_payload
    );
  }
}
