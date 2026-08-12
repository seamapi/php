<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Http\ResolveActionAttempt;
use Seam\Resources\ActionAttempt;
use Seam\Resources\ThermostatDailyProgram;

class ThermostatsDailyProgramsClient
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
     * Creates a new thermostat daily program. A daily program consists of a set of periods, where each period includes a start time and the key of a configured climate preset. Once you have defined a daily program, you can assign it to one or more days within a weekly program.
     *
     * @param string $device_id ID of the thermostat device for which you want to create a daily program.
     * @param string $name Name of the thermostat daily program.
     * @param array $periods Array of thermostat daily program periods.
     * @return ThermostatDailyProgram OK
     */
    public function create(
        string $device_id,
        string $name,
        array $periods,
    ): ThermostatDailyProgram {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        $request_payload["name"] = $name;
        $request_payload["periods"] = $periods;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/thermostats/daily_programs/create",
                ["json" => (object) $request_payload],
            ),
        );

        return ThermostatDailyProgram::from_json(
            $res->thermostat_daily_program,
        );
    }

    /**
     * Deletes a thermostat daily program.
     *
     * @param string $thermostat_daily_program_id ID of the thermostat daily program that you want to delete.
     * @return void OK
     */
    public function delete(string $thermostat_daily_program_id): void
    {
        $request_payload = [];

        $request_payload[
            "thermostat_daily_program_id"
        ] = $thermostat_daily_program_id;

        $this->client->request("POST", "/thermostats/daily_programs/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    /**
     * Updates a specified thermostat daily program. The periods that you specify overwrite any existing periods for the daily program.
     *
     * @param string $name Name of the thermostat daily program that you want to update.
     * @param array $periods Array of thermostat daily program periods. The periods that you specify overwrite any existing periods for the daily program.
     * @param string $thermostat_daily_program_id ID of the thermostat daily program that you want to update.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function update(
        string $name,
        array $periods,
        string $thermostat_daily_program_id,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["name"] = $name;
        $request_payload["periods"] = $periods;
        $request_payload[
            "thermostat_daily_program_id"
        ] = $thermostat_daily_program_id;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/thermostats/daily_programs/update",
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
