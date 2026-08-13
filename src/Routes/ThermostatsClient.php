<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Http\ResolveActionAttempt;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Device;

class ThermostatsClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public ThermostatsDailyProgramsClient $daily_programs;
    public ThermostatsSchedulesClient $schedules;
    public ThermostatsSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->daily_programs = new ThermostatsDailyProgramsClient(
            $client,
            $defaults,
        );
        $this->schedules = new ThermostatsSchedulesClient($client, $defaults);
        $this->simulate = new ThermostatsSimulateClient($client, $defaults);
    }

    /**
     * Activates a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to activate.
     * @param string $device_id ID of the thermostat device for which you want to activate a climate preset.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function activate_climate_preset(
        string $climate_preset_key,
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/thermostats/activate_climate_preset",
                ["json" => (object) $request_payload],
            ),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [cool mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to cool mode.
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
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

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/cool", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Creates a [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @param string $device_id ID of the thermostat device for which you want create a climate preset.
     * @param string $climate_preset_mode The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
     * @param float $cooling_set_point_celsius Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $cooling_set_point_fahrenheit Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param mixed $ecobee_metadata Metadata specific to the Ecobee climate, if applicable.
     * @param string $fan_mode_setting Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
     * @param float $heating_set_point_celsius Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $heating_set_point_fahrenheit Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param string $hvac_mode_setting Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
     * @param bool $manual_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings.
     * @param string $name User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @return void OK
     */
    public function create_climate_preset(
        string $climate_preset_key,
        string $device_id,
        ?string $climate_preset_mode = null,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        mixed $ecobee_metadata = null,
        ?string $fan_mode_setting = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?string $hvac_mode_setting = null,
        ?bool $manual_override_allowed = null,
        ?string $name = null,
    ): void {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;
        if ($climate_preset_mode !== null) {
            $request_payload["climate_preset_mode"] = $climate_preset_mode;
        }
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
        if ($ecobee_metadata !== null) {
            $request_payload["ecobee_metadata"] = $ecobee_metadata;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
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
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $this->client->request("POST", "/thermostats/create_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Deletes a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to delete.
     * @param string $device_id ID of the thermostat device for which you want to delete a climate preset.
     * @return void OK
     */
    public function delete_climate_preset(
        string $climate_preset_key,
        string $device_id,
    ): void {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;

        $this->client->request("DELETE", "/thermostats/delete_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [heat mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to heat mode.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function heat(
        string $device_id,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
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

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/heat", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [heat-cool ("auto") mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to heat-cool mode.
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function heat_cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
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

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/heat_cool", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Returns a list of all [thermostats](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param string $device_type Device type by which you want to filter thermostat devices.
     * @param array $device_types Array of device types by which you want to filter thermostat devices.
     * @param string $manufacturer Manufacturer by which you want to filter thermostat devices.
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
            $this->client->request("POST", "/thermostats/list", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to ["off" mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to off mode.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function off(
        string $device_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/off", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Sets a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) as the ["fallback"](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets/setting-the-fallback-climate-preset) preset for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to set as the fallback climate preset.
     * @param string $device_id ID of the thermostat device for which you want to set the fallback climate preset.
     * @return void OK
     */
    public function set_fallback_climate_preset(
        string $climate_preset_key,
        string $device_id,
    ): void {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;

        $this->client->request(
            "POST",
            "/thermostats/set_fallback_climate_preset",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Sets the [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to set the fan mode.
     * @param string $fan_mode Fan mode setting for the thermostat, such as `auto`, `on`, or `circulate`.
     * @param string $fan_mode_setting [Fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings) that you want to set for the thermostat.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function set_fan_mode(
        string $device_id,
        ?string $fan_mode = null,
        ?string $fan_mode_setting = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($fan_mode !== null) {
            $request_payload["fan_mode"] = $fan_mode;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/set_fan_mode", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Sets the [HVAC mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to set the HVAC mode.
     * @param string $hvac_mode_setting
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function set_hvac_mode(
        string $device_id,
        string $hvac_mode_setting,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
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

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/set_hvac_mode", [
                "json" => (object) $request_payload,
            ]),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Sets a [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) for a specified thermostat. Seam emits a `thermostat.temperature_threshold_exceeded` event and adds a warning on a thermostat if it reports a temperature outside the threshold range.
     *
     * @param string $device_id ID of the thermostat device for which you want to set a temperature threshold.
     * @param float $lower_limit_celsius Lower temperature limit in in °C. Seam alerts you if the reported temperature is lower than this value. You can specify either `lower_limit` but not both.
     * @param float $lower_limit_fahrenheit Lower temperature limit in in °F. Seam alerts you if the reported temperature is lower than this value. You can specify either `lower_limit` but not both.
     * @param float $upper_limit_celsius Upper temperature limit in in °C. Seam alerts you if the reported temperature is higher than this value. You can specify either `upper_limit` but not both.
     * @param float $upper_limit_fahrenheit Upper temperature limit in in °C. Seam alerts you if the reported temperature is higher than this value. You can specify either `upper_limit` but not both.
     * @return void OK
     */
    public function set_temperature_threshold(
        string $device_id,
        ?float $lower_limit_celsius = null,
        ?float $lower_limit_fahrenheit = null,
        ?float $upper_limit_celsius = null,
        ?float $upper_limit_fahrenheit = null,
    ): void {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($lower_limit_celsius !== null) {
            $request_payload["lower_limit_celsius"] = $lower_limit_celsius;
        }
        if ($lower_limit_fahrenheit !== null) {
            $request_payload[
                "lower_limit_fahrenheit"
            ] = $lower_limit_fahrenheit;
        }
        if ($upper_limit_celsius !== null) {
            $request_payload["upper_limit_celsius"] = $upper_limit_celsius;
        }
        if ($upper_limit_fahrenheit !== null) {
            $request_payload[
                "upper_limit_fahrenheit"
            ] = $upper_limit_fahrenheit;
        }

        $this->client->request(
            "PATCH",
            "/thermostats/set_temperature_threshold",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Updates a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @param string $device_id ID of the thermostat device for which you want to update a climate preset.
     * @param string $climate_preset_mode The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
     * @param float $cooling_set_point_celsius Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $cooling_set_point_fahrenheit Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param mixed $ecobee_metadata Metadata specific to the Ecobee climate, if applicable.
     * @param string $fan_mode_setting Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
     * @param float $heating_set_point_celsius Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $heating_set_point_fahrenheit Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param string $hvac_mode_setting Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
     * @param bool $manual_override_allowed Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @return void OK
     */
    public function update_climate_preset(
        string $climate_preset_key,
        string $device_id,
        ?string $climate_preset_mode = null,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        mixed $ecobee_metadata = null,
        ?string $fan_mode_setting = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?string $hvac_mode_setting = null,
        ?bool $manual_override_allowed = null,
        ?string $name = null,
    ): void {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;
        if ($climate_preset_mode !== null) {
            $request_payload["climate_preset_mode"] = $climate_preset_mode;
        }
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
        if ($ecobee_metadata !== null) {
            $request_payload["ecobee_metadata"] = $ecobee_metadata;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
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
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $this->client->request("PATCH", "/thermostats/update_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates the thermostat weekly program for a thermostat device. To configure a weekly program, specify the ID of the daily program that you want to use for each day of the week. When you update a weekly program, the set of programs that you specify overwrites any previous weekly program for the thermostat.
     *
     * @param string $device_id ID of the thermostat device for which you want to update the weekly program.
     * @param string $friday_program_id ID of the thermostat daily program to run on Fridays.
     * @param string $monday_program_id ID of the thermostat daily program to run on Mondays.
     * @param string $saturday_program_id ID of the thermostat daily program to run on Saturdays.
     * @param string $sunday_program_id ID of the thermostat daily program to run on Sundays.
     * @param string $thursday_program_id ID of the thermostat daily program to run on Thursdays.
     * @param string $tuesday_program_id ID of the thermostat daily program to run on Tuesdays.
     * @param string $wednesday_program_id ID of the thermostat daily program to run on Wednesdays.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function update_weekly_program(
        string $device_id,
        ?string $friday_program_id = null,
        ?string $monday_program_id = null,
        ?string $saturday_program_id = null,
        ?string $sunday_program_id = null,
        ?string $thursday_program_id = null,
        ?string $tuesday_program_id = null,
        ?string $wednesday_program_id = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($friday_program_id !== null) {
            $request_payload["friday_program_id"] = $friday_program_id;
        }
        if ($monday_program_id !== null) {
            $request_payload["monday_program_id"] = $monday_program_id;
        }
        if ($saturday_program_id !== null) {
            $request_payload["saturday_program_id"] = $saturday_program_id;
        }
        if ($sunday_program_id !== null) {
            $request_payload["sunday_program_id"] = $sunday_program_id;
        }
        if ($thursday_program_id !== null) {
            $request_payload["thursday_program_id"] = $thursday_program_id;
        }
        if ($tuesday_program_id !== null) {
            $request_payload["tuesday_program_id"] = $tuesday_program_id;
        }
        if ($wednesday_program_id !== null) {
            $request_payload["wednesday_program_id"] = $wednesday_program_id;
        }

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/thermostats/update_weekly_program",
                ["json" => (object) $request_payload],
            ),
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }
}
