<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class Bridges
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $bridge_id): void
    {
        $request_payload = [];

        if ($bridge_id !== null) {
            $request_payload["bridge_id"] = $bridge_id;
        }

        $this->seam->client->post("/bridges/get", [
            "json" => (object) $request_payload,
        ]);
    }

    public function list(): void
    {
        $request_payload = [];

        $this->seam->client->post("/bridges/list", [
            "json" => (object) $request_payload,
        ]);
    }
}
