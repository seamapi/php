<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\EnrollmentAutomation;

class UserIdentitiesEnrollmentAutomationsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function delete(string $enrollment_automation_id): void
  {
    $request_payload = [];

    if ($enrollment_automation_id !== null) {
      $request_payload["enrollment_automation_id"] = $enrollment_automation_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/enrollment_automations/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $enrollment_automation_id): EnrollmentAutomation
  {
    $request_payload = [];

    if ($enrollment_automation_id !== null) {
      $request_payload["enrollment_automation_id"] = $enrollment_automation_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/enrollment_automations/get",
      json: (object) $request_payload,
      inner_object: "enrollment_automation"
    );

    return EnrollmentAutomation::from_json($res);
  }

  public function launch(
    string $credential_manager_acs_system_id,
    string $user_identity_id,
    string $acs_credential_pool_id = null,
    bool $create_credential_manager_user = null,
    string $credential_manager_acs_user_id = null
  ): void {
    $request_payload = [];

    if ($credential_manager_acs_system_id !== null) {
      $request_payload["credential_manager_acs_system_id"] = $credential_manager_acs_system_id;
    }
    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }
    if ($acs_credential_pool_id !== null) {
      $request_payload["acs_credential_pool_id"] = $acs_credential_pool_id;
    }
    if ($create_credential_manager_user !== null) {
      $request_payload["create_credential_manager_user"] = $create_credential_manager_user;
    }
    if ($credential_manager_acs_user_id !== null) {
      $request_payload["credential_manager_acs_user_id"] = $credential_manager_acs_user_id;
    }

    $this->seam->request(
      "POST",
      "/user_identities/enrollment_automations/launch",
      json: (object) $request_payload,
      inner_object: "enrollment_automation"
    );
  }

  public function list(string $user_identity_id): array
  {
    $request_payload = [];

    if ($user_identity_id !== null) {
      $request_payload["user_identity_id"] = $user_identity_id;
    }

    $res = $this->seam->request(
      "POST",
      "/user_identities/enrollment_automations/list",
      json: (object) $request_payload,
      inner_object: "enrollment_automations"
    );

    return array_map(fn($r) => EnrollmentAutomation::from_json($r), $res);
  }
}
