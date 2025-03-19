<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Network;

class Networks
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $network_id): Network
    {
        $request_payload = [];

        if ($network_id !== null) {
            $request_payload["network_id"] = $network_id;
        }

        $res = $this->seam->client->post("/networks/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Network::from_json($json->network);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->client->post("/networks/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Network::from_json($r), $json->networks);
    }
}
