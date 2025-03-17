<?php

namespace Seam\Routes;

use Seam\SeamClient;
use Seam\Routes\Clients\AccessCodesClient;
use Seam\Routes\Clients\AcsClient;
use Seam\Routes\Clients\ActionAttemptsClient;
use Seam\Routes\Clients\BridgesClient;
use Seam\Routes\Clients\ClientSessionsClient;
use Seam\Routes\Clients\ConnectWebviewsClient;
use Seam\Routes\Clients\ConnectedAccountsClient;
use Seam\Routes\Clients\DevicesClient;
use Seam\Routes\Clients\EventsClient;
use Seam\Routes\Clients\LocksClient;
use Seam\Routes\Clients\NetworksClient;
use Seam\Routes\Clients\NoiseSensorsClient;
use Seam\Routes\Clients\PhonesClient;
use Seam\Routes\Clients\ThermostatsClient;
use Seam\Routes\Clients\UserIdentitiesClient;
use Seam\Routes\Clients\WebhooksClient;
use Seam\Routes\Clients\WorkspacesClient;

class Routes
{
  public AccessCodesClient $access_codes;
  public AcsClient $acs;
  public ActionAttemptsClient $action_attempts;
  public BridgesClient $bridges;
  public ClientSessionsClient $client_sessions;
  public ConnectWebviewsClient $connect_webviews;
  public ConnectedAccountsClient $connected_accounts;
  public DevicesClient $devices;
  public EventsClient $events;
  public LocksClient $locks;
  public NetworksClient $networks;
  public NoiseSensorsClient $noise_sensors;
  public PhonesClient $phones;
  public ThermostatsClient $thermostats;
  public UserIdentitiesClient $user_identities;
  public WebhooksClient $webhooks;
  public WorkspacesClient $workspaces;

  public function __construct(SeamClient $seam)
  {
    $this->access_codes = new AccessCodesClient($seam);
    $this->acs = new AcsClient($seam);
    $this->action_attempts = new ActionAttemptsClient($seam);
    $this->bridges = new BridgesClient($seam);
    $this->client_sessions = new ClientSessionsClient($seam);
    $this->connect_webviews = new ConnectWebviewsClient($seam);
    $this->connected_accounts = new ConnectedAccountsClient($seam);
    $this->devices = new DevicesClient($seam);
    $this->events = new EventsClient($seam);
    $this->locks = new LocksClient($seam);
    $this->networks = new NetworksClient($seam);
    $this->noise_sensors = new NoiseSensorsClient($seam);
    $this->phones = new PhonesClient($seam);
    $this->thermostats = new ThermostatsClient($seam);
    $this->user_identities = new UserIdentitiesClient($seam);
    $this->webhooks = new WebhooksClient($seam);
    $this->workspaces = new WorkspacesClient($seam);
  }
}
