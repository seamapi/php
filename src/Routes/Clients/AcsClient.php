<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class AcsClient
{
  private Seam $seam;
  public AcsAccessGroupsClient $access_groups;
  public AcsCredentialPoolsClient $credential_pools;
  public AcsCredentialProvisioningAutomationsClient $credential_provisioning_automations;
  public AcsCredentialsClient $credentials;
  public AcsEncodersClient $encoders;
  public AcsEntrancesClient $entrances;
  public AcsSystemsClient $systems;
  public AcsUsersClient $users;
  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
    $this->access_groups = new AcsAccessGroupsClient($seam);
    $this->credential_pools = new AcsCredentialPoolsClient($seam);
    $this->credential_provisioning_automations = new AcsCredentialProvisioningAutomationsClient(
      $seam
    );
    $this->credentials = new AcsCredentialsClient($seam);
    $this->encoders = new AcsEncodersClient($seam);
    $this->entrances = new AcsEntrancesClient($seam);
    $this->systems = new AcsSystemsClient($seam);
    $this->users = new AcsUsersClient($seam);
  }
}
