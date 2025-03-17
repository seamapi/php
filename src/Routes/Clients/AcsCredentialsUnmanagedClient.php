<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\UnmanagedAcsCredential;

class AcsCredentialsUnmanagedClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $acs_credential_id): UnmanagedAcsCredential
  {
    $request_payload = [];

    if ($acs_credential_id !== null) {
      $request_payload["acs_credential_id"] = $acs_credential_id;
    }

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/unmanaged/get",
      json: (object) $request_payload,
      inner_object: "acs_credential"
    );

    return UnmanagedAcsCredential::from_json($res);
  }

  public function list(
    string $acs_user_id = null,
    string $acs_system_id = null,
    string $user_identity_id = null
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

    $res = $this->seam->request(
      "POST",
      "/acs/credentials/unmanaged/list",
      json: (object) $request_payload,
      inner_object: "acs_credentials"
    );

    return array_map(fn($r) => UnmanagedAcsCredential::from_json($r), $res);
  }
}
