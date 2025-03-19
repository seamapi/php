<?php

namespace Seam\Routes;

use Seam\Seam;
use Seam\Routes\Clients\AccessCodes;
use Seam\Routes\Clients\Acs;
use Seam\Routes\Clients\ActionAttempts;
use Seam\Routes\Clients\Bridges;
use Seam\Routes\Clients\ClientSessions;
use Seam\Routes\Clients\ConnectWebviews;
use Seam\Routes\Clients\ConnectedAccounts;
use Seam\Routes\Clients\Devices;
use Seam\Routes\Clients\Events;
use Seam\Routes\Clients\Locks;
use Seam\Routes\Clients\Networks;
use Seam\Routes\Clients\NoiseSensors;
use Seam\Routes\Clients\Phones;
use Seam\Routes\Clients\Thermostats;
use Seam\Routes\Clients\UserIdentities;
use Seam\Routes\Clients\Webhooks;
use Seam\Routes\Clients\Workspaces;

class Routes
{
    public AccessCodes $access_codes;
    public Acs $acs;
    public ActionAttempts $action_attempts;
    public Bridges $bridges;
    public ClientSessions $client_sessions;
    public ConnectWebviews $connect_webviews;
    public ConnectedAccounts $connected_accounts;
    public Devices $devices;
    public Events $events;
    public Locks $locks;
    public Networks $networks;
    public NoiseSensors $noise_sensors;
    public Phones $phones;
    public Thermostats $thermostats;
    public UserIdentities $user_identities;
    public Webhooks $webhooks;
    public Workspaces $workspaces;

    public function __construct(Seam $seam)
    {
        $this->access_codes = new AccessCodes($seam);
        $this->acs = new Acs($seam);
        $this->action_attempts = new ActionAttempts($seam);
        $this->bridges = new Bridges($seam);
        $this->client_sessions = new ClientSessions($seam);
        $this->connect_webviews = new ConnectWebviews($seam);
        $this->connected_accounts = new ConnectedAccounts($seam);
        $this->devices = new Devices($seam);
        $this->events = new Events($seam);
        $this->locks = new Locks($seam);
        $this->networks = new Networks($seam);
        $this->noise_sensors = new NoiseSensors($seam);
        $this->phones = new Phones($seam);
        $this->thermostats = new Thermostats($seam);
        $this->user_identities = new UserIdentities($seam);
        $this->webhooks = new Webhooks($seam);
        $this->workspaces = new Workspaces($seam);
    }
}
