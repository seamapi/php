<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\NoiseThreshold;

class NoiseSensorsNoiseThresholdsClient
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
     * Creates a new [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors). Thresholds represent the limits of noise tolerated at a property, which can be customized for each hour of the day. Each device has its own default thresholds, but you can use the Seam API to modify them.
     *
     * @param string $device_id ID of the device for which you want to create a noise threshold.
     * @param string $ends_daily_at Time at which the new noise threshold should become inactive daily.
     * @param string $starts_daily_at Time at which the new noise threshold should become active daily.
     * @param string $name Name of the new noise threshold.
     * @param float $noise_threshold_decibels Noise level in decibels for the new noise threshold.
     * @param float $noise_threshold_nrs Noise level in Noiseaware Noise Risk Score (NRS) for the new noise threshold. This parameter is only relevant for [Noiseaware sensors](https://docs.seam.co/device-and-system-integration-guides/noiseaware-sensors).
     * @return NoiseThreshold OK
     */
    public function create(
        string $device_id,
        string $ends_daily_at,
        string $starts_daily_at,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
    ): NoiseThreshold {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["ends_daily_at"] = $ends_daily_at;
        $request_payload["starts_daily_at"] = $starts_daily_at;
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($noise_threshold_decibels !== null) {
            $request_payload[
                "noise_threshold_decibels"
            ] = $noise_threshold_decibels;
        }
        if ($noise_threshold_nrs !== null) {
            $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
        }

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/noise_sensors/noise_thresholds/create",
                ["json" => (object) $request_payload],
            ),
        );

        return NoiseThreshold::from_json($res->noise_threshold);
    }

    /**
     * Deletes a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) from a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device that contains the noise threshold that you want to delete.
     * @param string $noise_threshold_id ID of the noise threshold that you want to delete.
     * @return void OK
     */
    public function delete(string $device_id, string $noise_threshold_id): void
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["noise_threshold_id"] = $noise_threshold_id;

        $this->client->request(
            "DELETE",
            "/noise_sensors/noise_thresholds/delete",
            ["query" => $request_payload],
        );
    }

    /**
     * Returns a specified [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $noise_threshold_id ID of the noise threshold that you want to get.
     * @return NoiseThreshold OK
     */
    public function get(string $noise_threshold_id): NoiseThreshold
    {
        $request_payload = [];

        $request_payload["noise_threshold_id"] = $noise_threshold_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/noise_sensors/noise_thresholds/get",
                ["query" => $request_payload],
            ),
        );

        return NoiseThreshold::from_json($res->noise_threshold);
    }

    /**
     * Returns a list of all [noise thresholds](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device for which you want to list noise thresholds.
     * @return array OK
     */
    public function list(string $device_id): array
    {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request(
                "GET",
                "/noise_sensors/noise_thresholds/list",
                ["query" => $request_payload],
            ),
        );

        return array_map(
            fn($r) => NoiseThreshold::from_json($r),
            $res->noise_thresholds,
        );
    }

    /**
     * Updates a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device that contains the noise threshold that you want to update.
     * @param string $noise_threshold_id ID of the noise threshold that you want to update.
     * @param string $ends_daily_at Time at which the noise threshold should become inactive daily.
     * @param string $name Name of the noise threshold that you want to update.
     * @param float $noise_threshold_decibels Noise level in decibels for the noise threshold.
     * @param float $noise_threshold_nrs Noise level in Noiseaware Noise Risk Score (NRS) for the noise threshold. This parameter is only relevant for [Noiseaware sensors](https://docs.seam.co/device-and-system-integration-guides/noiseaware-sensors).
     * @param string $starts_daily_at Time at which the noise threshold should become active daily.
     * @return void OK
     */
    public function update(
        string $device_id,
        string $noise_threshold_id,
        ?string $ends_daily_at = null,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
        ?string $starts_daily_at = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["noise_threshold_id"] = $noise_threshold_id;
        if ($ends_daily_at !== null) {
            $request_payload["ends_daily_at"] = $ends_daily_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($noise_threshold_decibels !== null) {
            $request_payload[
                "noise_threshold_decibels"
            ] = $noise_threshold_decibels;
        }
        if ($noise_threshold_nrs !== null) {
            $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
        }
        if ($starts_daily_at !== null) {
            $request_payload["starts_daily_at"] = $starts_daily_at;
        }

        $this->client->request(
            "PUT",
            "/noise_sensors/noise_thresholds/update",
            ["json" => (object) $request_payload],
        );
    }
}
