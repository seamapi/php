<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class NoiseSensorsSimulate
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function trigger_noise_threshold(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->client->post(
            "/noise_sensors/simulate/trigger_noise_threshold",
            ["json" => (object) $request_payload]
        );
    }
}
