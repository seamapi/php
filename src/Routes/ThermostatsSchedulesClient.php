<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\ThermostatSchedule;

class ThermostatsSchedulesClient
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
     * Creates a new [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the new thermostat schedule.
     * @param string $device_id ID of the thermostat device for which you want to create a schedule.
     * @param string $ends_at Date and time at which the new thermostat schedule ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $starts_at Date and time at which the new thermostat schedule starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $is_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings while the new schedule is active. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param int|NullValue $max_override_period_minutes Number of minutes for which a person at the thermostat or using the API can change the thermostat's settings after the activation of the scheduled climate preset. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name Name of the thermostat schedule.
     * @return ThermostatSchedule OK
     */
    public function create(
        string $climate_preset_key,
        string $device_id,
        string $ends_at,
        string $starts_at,
        ?bool $is_override_allowed = null,
        int|NullValue|null $max_override_period_minutes = null,
        ?string $name = null,
    ): ThermostatSchedule {
        $request_payload = [];

        $request_payload["climate_preset_key"] = $climate_preset_key;
        $request_payload["device_id"] = $device_id;
        $request_payload["ends_at"] = $ends_at;
        $request_payload["starts_at"] = $starts_at;
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = Body::decode(
            $this->client->request("POST", "/thermostats/schedules/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return ThermostatSchedule::from_json($res->thermostat_schedule);
    }

    /**
     * Deletes a [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to delete.
     * @return void OK
     */
    public function delete(string $thermostat_schedule_id): void
    {
        $request_payload = [];

        $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;

        $this->client->request("DELETE", "/thermostats/schedules/delete", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Returns a specified [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to get.
     * @return ThermostatSchedule OK
     */
    public function get(string $thermostat_schedule_id): ThermostatSchedule
    {
        $request_payload = [];

        $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;

        $res = Body::decode(
            $this->client->request("GET", "/thermostats/schedules/get", [
                "query" => $request_payload,
            ]),
        );

        return ThermostatSchedule::from_json($res->thermostat_schedule);
    }

    /**
     * Returns a list of all [thermostat schedules](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to list schedules.
     * @param string $user_identifier_key User identifier key by which to filter the list of returned thermostat schedules.
     * @return array OK
     */
    public function list(
        string $device_id,
        ?string $user_identifier_key = null,
    ): array {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = Body::decode(
            $this->client->request("GET", "/thermostats/schedules/list", [
                "query" => $request_payload,
            ]),
        );

        return array_map(
            fn($r) => ThermostatSchedule::from_json($r),
            $res->thermostat_schedules,
        );
    }

    /**
     * Updates a specified [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to update.
     * @param string $climate_preset_key Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the thermostat schedule.
     * @param string $ends_at Date and time at which the thermostat schedule ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $is_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings while the schedule is active. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param int|NullValue $max_override_period_minutes Number of minutes for which a person at the thermostat or using the API can change the thermostat's settings after the activation of the scheduled climate preset. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name Name of the thermostat schedule.
     * @param string $starts_at Date and time at which the thermostat schedule starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return void OK
     */
    public function update(
        string $thermostat_schedule_id,
        ?string $climate_preset_key = null,
        ?string $ends_at = null,
        ?bool $is_override_allowed = null,
        int|NullValue|null $max_override_period_minutes = null,
        ?string $name = null,
        ?string $starts_at = null,
    ): void {
        $request_payload = [];

        $request_payload["thermostat_schedule_id"] = $thermostat_schedule_id;
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->client->request("PATCH", "/thermostats/schedules/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
