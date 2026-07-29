<?php

namespace Seam\Routes;

use Seam\Resources\ActionAttempt;
use Seam\Resources\ThermostatDailyProgram;
use Seam\SeamClient;

class ThermostatsDailyProgramsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
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

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($periods !== null) {
            $request_payload["periods"] = $periods;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/daily_programs/create",
            json: (object) $request_payload,
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

        if ($thermostat_daily_program_id !== null) {
            $request_payload[
                "thermostat_daily_program_id"
            ] = $thermostat_daily_program_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/daily_programs/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Updates a specified thermostat daily program. The periods that you specify overwrite any existing periods for the daily program.
     *
     * @param string $name Name of the thermostat daily program that you want to update.
     * @param array $periods Array of thermostat daily program periods. The periods that you specify overwrite any existing periods for the daily program.
     * @param string $thermostat_daily_program_id ID of the thermostat daily program that you want to update.
     * @return ActionAttempt OK
     */
    public function update(
        string $name,
        array $periods,
        string $thermostat_daily_program_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($periods !== null) {
            $request_payload["periods"] = $periods;
        }
        if ($thermostat_daily_program_id !== null) {
            $request_payload[
                "thermostat_daily_program_id"
            ] = $thermostat_daily_program_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/daily_programs/update",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}
