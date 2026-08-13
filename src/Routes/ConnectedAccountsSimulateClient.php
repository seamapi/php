<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;

class ConnectedAccountsSimulateClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Simulates a connected account becoming disconnected from Seam. Only applicable for [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $connected_account_id ID of the connected account you want to simulate as disconnected.
     * @return void OK
     */
    public function disconnect(string $connected_account_id): void
    {
        $request_payload = [];

        $request_payload["connected_account_id"] = $connected_account_id;

        $this->client->request(
            "POST",
            "/connected_accounts/simulate/disconnect",
            ["json" => (object) $request_payload],
        );
    }
}
