<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;

class AcsClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public AcsAccessGroupsClient $access_groups;
    public AcsCredentialsClient $credentials;
    public AcsEncodersClient $encoders;
    public AcsEntrancesClient $entrances;
    public AcsSystemsClient $systems;
    public AcsUsersClient $users;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->access_groups = new AcsAccessGroupsClient($client, $defaults);
        $this->credentials = new AcsCredentialsClient($client, $defaults);
        $this->encoders = new AcsEncodersClient($client, $defaults);
        $this->entrances = new AcsEntrancesClient($client, $defaults);
        $this->systems = new AcsSystemsClient($client, $defaults);
        $this->users = new AcsUsersClient($client, $defaults);
    }
}
