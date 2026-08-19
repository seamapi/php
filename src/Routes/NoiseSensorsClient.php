<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\Device;

class NoiseSensorsClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public NoiseSensorsNoiseThresholdsClient $noise_thresholds;
    public NoiseSensorsSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->noise_thresholds = new NoiseSensorsNoiseThresholdsClient(
            $client,
            $defaults,
        );
        $this->simulate = new NoiseSensorsSimulateClient($client, $defaults);
    }

    /**
     * Returns a list of all [noise sensors](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param string $device_type Device type of the noise sensors that you want to list.
     * @param array $device_types Device types of the noise sensors that you want to list.
     * @param string $manufacturer Manufacturers of the noise sensors that you want to list.
     * @return array OK
     */
    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?string $manufacturer = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }

        $res = Body::decode(
            $this->client->request("GET", "/noise_sensors/list", [
                "query" => $request_payload,
            ]),
        );

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }
}
