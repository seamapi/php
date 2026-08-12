<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;

class DevicesSimulateClient
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
     * Simulates connecting a device to Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate connecting to Seam.
     * @return void OK
     */
    public function connect(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request("POST", "/devices/simulate/connect", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Simulates bringing the Wi‑Fi hub (bridge) back online for a device.
     * Only applicable for sandbox workspaces and currently
     * implemented for August and TTLock locks.
     * This will clear the `hub_disconnected` error on the device.
     *
     * @param string $device_id ID of the device whose hub you want to reconnect.
     * @return void OK
     */
    public function connect_to_hub(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request("POST", "/devices/simulate/connect_to_hub", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Simulates disconnecting a device from Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate disconnecting from Seam.
     * @return void OK
     */
    public function disconnect(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request("POST", "/devices/simulate/disconnect", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Simulates taking the Wi‑Fi hub (bridge) offline for a device.
     * Only applicable for sandbox workspaces and currently
     * implemented for August, TTLock, and IglooHome devices.
     * This will set the `hub_disconnected` error on the device, or mark the
     * IglooHome bridge offline in sandbox.
     *
     * @param string $device_id ID of the device whose hub you want to disconnect.
     * @return void OK
     */
    public function disconnect_from_hub(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request(
            "POST",
            "/devices/simulate/disconnect_from_hub",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Toggle the simulated Nuki Smart Hosting subscription for a device (sandbox only).
     * Send `is_expired: true` to simulate an expired subscription, or `false` to simulate an active subscription.
     * The actual device error is created/cleared by the poller after this state change.
     *
     * @param string $device_id
     * @param bool $is_expired
     * @return void OK
     */
    public function paid_subscription(string $device_id, bool $is_expired): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["is_expired"] = $is_expired;

        $this->client->request("POST", "/devices/simulate/paid_subscription", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Simulates removing a device from Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate removing from Seam.
     * @return void OK
     */
    public function remove(string $device_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $this->client->request("POST", "/devices/simulate/remove", [
            "json" => (object) $request_payload,
        ]);
    }
}
