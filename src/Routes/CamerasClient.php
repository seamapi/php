<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;

class CamerasClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public CamerasLiveViewsClient $live_views;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->live_views = new CamerasLiveViewsClient($client, $defaults);
    }
}
