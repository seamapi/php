<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\UserIdentity;
use Seam\Routes\Objects\Device;
use Seam\Routes\Objects\AcsSystem;
use Seam\Routes\Objects\AcsUser;

class UserIdentitiesClient
{
  private SeamClient $seam;
  public UserIdentitiesEnrollmentAutomationsClient $enrollment_automations;
  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
    $this->enrollment_automations = new UserIdentitiesEnrollmentAutomationsClient(
      $seam
    );
  }

  public function add_acs_user(
    string $acs_user_id,
    string $user_identity_id
  ): void {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/add_acs_user",
      json: (object) $request_payload
    );
  }

  public function create(
    string $email_address = null,
    string $full_name = null,
    string $phone_number = null,
    string $user_identity_key = null
  ): UserIdentity {
    $request_payload = [];

    if ($email_address !== null) {
      $request_payload["email_address"] = $email_address;
    }
    if ($full_name !== null) {
      $request_payload["full_name"] = $full_name;
    }
    if ($phone_number !== null) {
      $request_payload["phone_number"] = $phone_number;
    }
    if ($user_identity_key !== null) {
      $request_payload["user_identity_key"] = $user_identity_key;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/create",
      json: (object) $request_payload,
      inner_object: "user_identity"
    );

    return UserIdentity::from_json($res);
  }

  public function delete(string $user_identity_id): void
  {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/delete",
      json: (object) $request_payload
    );
  }

  public function get(
    string $user_identity_id = null,
    string $user_identity_key = null
  ): UserIdentity {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($user_identity_key !== null) {
      $request_payload["user_identity_key"] = $user_identity_key;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/get",
      json: (object) $request_payload,
      inner_object: "user_identity"
    );

    return UserIdentity::from_json($res);
  }

  public function grant_access_to_device(
    string $device_id,
    string $user_identity_id
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/grant_access_to_device",
      json: (object) $request_payload
    );
  }

  public function list(string $credential_manager_acs_system_id = null): array
  {
    $request_payload = [];

    if ($credential_manager_acs_system_id !== null) {
      $request_payload["credential_manager_acs_system_id"] = $credential_manager_acs_system_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/list",
      json: (object) $request_payload,
      inner_object: "user_identities"
    );

    return array_map(fn($r) => UserIdentity::from_json($r), $res);
  }

  public function list_accessible_devices(string $user_identity_id): array
  {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/list_accessible_devices",
      json: (object) $request_payload,
      inner_object: "devices"
    );

    return array_map(fn($r) => Device::from_json($r), $res);
  }

  public function list_acs_systems(string $user_identity_id): array
  {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/list_acs_systems",
      json: (object) $request_payload,
      inner_object: "acs_systems"
    );

    return array_map(fn($r) => AcsSystem::from_json($r), $res);
  }

  public function list_acs_users(string $user_identity_id): array
  {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/list_acs_users",
      json: (object) $request_payload,
      inner_object: "acs_users"
    );

    return array_map(fn($r) => AcsUser::from_json($r), $res);
  }

  public function remove_acs_user(
    string $acs_user_id,
    string $user_identity_id
  ): void {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/remove_acs_user",
      json: (object) $request_payload
    );
  }

  public function revoke_access_to_device(
    string $device_id,
    string $user_identity_id
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/revoke_access_to_device",
      json: (object) $request_payload
    );
  }

  public function update(
    string $user_identity_id,
    string $email_address = null,
    string $full_name = null,
    string $phone_number = null,
    string $user_identity_key = null
  ): void {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($email_address !== null) {
      $request_payload["email_address"] = $email_address;
    }
    if ($full_name !== null) {
      $request_payload["full_name"] = $full_name;
    }
    if ($phone_number !== null) {
      $request_payload["phone_number"] = $phone_number;
    }
    if ($user_identity_key !== null) {
      $request_payload["user_identity_key"] = $user_identity_key;
    }

    $this->seam->request(
      "POST",
      "/user_identities/update",
      json: (object) $request_payload
    );
  }
}
