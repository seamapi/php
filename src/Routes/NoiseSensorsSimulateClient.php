<?php

namespace Seam\Routes;

use Seam\SeamClient;

class NoiseSensorsSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Simulates the triggering of a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors) in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $device_id ID of the device for which you want to simulate the triggering of a noise threshold.
     * @return void OK
     */
    public function trigger_noise_threshold(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/noise_sensors/simulate/trigger_noise_threshold",
            json: (object) $request_payload,
        );
    }
}
