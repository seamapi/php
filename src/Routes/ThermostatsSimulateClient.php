<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;

class ThermostatsSimulateClient
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
     * Simulates having adjusted the [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) for a [thermostat](https://docs.seam.co/capability-guides/thermostats). Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your Thermostat App with Simulate Endpoints](https://docs.seam.co/capability-guides/thermostats/testing-your-thermostat-app-with-simulate-endpoints).
     *
     * @param string $device_id ID of the thermostat device for which you want to simulate having adjusted the HVAC mode.
     * @param string $hvac_mode HVAC mode that you want to simulate.
     * @param float $cooling_set_point_celsius Cooling [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to simulate. You must set `cooling_set_point_celsius` or `cooling_set_point_fahrenheit`.
     * @param float $cooling_set_point_fahrenheit Cooling [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to simulate. You must set `cooling_set_point_fahrenheit` or `cooling_set_point_celsius`.
     * @param float $heating_set_point_celsius Heating [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to simulate. You must set `heating_set_point_celsius` or `heating_set_point_fahrenheit`.
     * @param float $heating_set_point_fahrenheit Heating [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to simulate. You must set `heating_set_point_fahrenheit` or `heating_set_point_celsius`.
     * @return void OK
     */
    public function hvac_mode_adjusted(
        string $device_id,
        string $hvac_mode,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["hvac_mode"] = $hvac_mode;
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }

        $this->client->request(
            "POST",
            "/thermostats/simulate/hvac_mode_adjusted",
            json: (object) $request_payload,
        );
    }

    /**
     * Simulates a [thermostat](https://docs.seam.co/capability-guides/thermostats) reaching a specified temperature. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your Thermostat App with Simulate Endpoints](https://docs.seam.co/capability-guides/thermostats/testing-your-thermostat-app-with-simulate-endpoints).
     *
     * @param string $device_id ID of the thermostat device that you want to simulate reaching a specified temperature.
     * @param float $temperature_celsius Temperature in °C that you want simulate the thermostat reaching. You must set `temperature_celsius` or `temperature_fahrenheit`.
     * @param float $temperature_fahrenheit Temperature in °F that you want simulate the thermostat reaching. You must set `temperature_fahrenheit` or `temperature_celsius`.
     * @return void OK
     */
    public function temperature_reached(
        string $device_id,
        ?float $temperature_celsius = null,
        ?float $temperature_fahrenheit = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($temperature_celsius !== null) {
            $request_payload["temperature_celsius"] = $temperature_celsius;
        }
        if ($temperature_fahrenheit !== null) {
            $request_payload[
                "temperature_fahrenheit"
            ] = $temperature_fahrenheit;
        }

        $this->client->request(
            "POST",
            "/thermostats/simulate/temperature_reached",
            json: (object) $request_payload,
        );
    }
}
