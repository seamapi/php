<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;

class NoiseSensorsSimulateClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
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

        $request_payload["device_id"] = $device_id;

        $this->client->request(
            "POST",
            "/noise_sensors/simulate/trigger_noise_threshold",
            json: (object) $request_payload,
        );
    }
}
