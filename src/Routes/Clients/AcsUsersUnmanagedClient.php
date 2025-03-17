<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\UnmanagedAcsUser;

class AcsUsersUnmanagedClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $acs_user_id): UnmanagedAcsUser
  {
    $request_payload = [];

    if ($acs_user_id !== null) {
      $request_payload["acs_user_id"] = $acs_user_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/users/unmanaged/get",
      json: (object) $request_payload,
      inner_object: "acs_user"
    );

    return UnmanagedAcsUser::from_json($res);
  }

  public function list(
    string $acs_system_id = null,
    float $limit = null,
    string $user_identity_email_address = null,
    string $user_identity_id = null,
    string $user_identity_phone_number = null
  ): array {
    $request_payload = [];

    if ($acs_system_id !== null) {
      $request_payload["acs_system_id"] = $acs_system_id;
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
      "/acs/users/unmanaged/list",
      json: (object) $request_payload,
      inner_object: "acs_users"
    );

    return array_map(fn($r) => UnmanagedAcsUser::from_json($r), $res);
  }
}
