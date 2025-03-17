<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\AcsCredentialProvisioningAutomation;

class AcsCredentialProvisioningAutomationsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function launch(
    string $credential_manager_acs_system_id,
    string $user_identity_id,
    string $acs_credential_pool_id = null,
    bool $create_credential_manager_user = null,
    string $credential_manager_acs_user_id = null
  ): AcsCredentialProvisioningAutomation {
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

    $res = $this->seam->request(
      "POST",
      "/acs/credential_provisioning_automations/launch",
      json: (object) $request_payload,
      inner_object: "acs_credential_provisioning_automation"
    );

    return AcsCredentialProvisioningAutomation::from_json($res);
  }
}
