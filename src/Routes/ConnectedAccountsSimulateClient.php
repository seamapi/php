<?php

namespace Seam\Routes;

use Seam\SeamClient;

class ConnectedAccountsSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
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

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/simulate/disconnect",
            json: (object) $request_payload,
        );
    }
}
