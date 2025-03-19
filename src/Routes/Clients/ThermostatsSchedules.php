<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ThermostatSchedule;

class ThermostatsSchedules
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $device_id,
        string $climate_preset_key,
        string $starts_at,
        string $ends_at,
        ?string $name = null,
        mixed $max_override_period_minutes = null,
        ?bool $is_override_allowed = null
    ): ThermostatSchedule {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }

        $res = $this->seam->client->post("/thermostats/schedules/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return ThermostatSchedule::from_json($json->thermostat_schedule);
    }

    public function delete(string $thermostat_schedule_id): void
    {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }

        $this->seam->client->post("/thermostats/schedules/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $thermostat_schedule_id): ThermostatSchedule
    {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }

        $res = $this->seam->client->post("/thermostats/schedules/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return ThermostatSchedule::from_json($json->thermostat_schedule);
    }

    public function list(
        string $device_id,
        ?string $user_identifier_key = null
    ): array {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->client->post("/thermostats/schedules/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => ThermostatSchedule::from_json($r),
            $json->thermostat_schedules
        );
    }

    public function update(
        string $thermostat_schedule_id,
        ?string $name = null,
        ?string $climate_preset_key = null,
        mixed $max_override_period_minutes = null,
        ?string $starts_at = null,
        ?string $ends_at = null,
        ?bool $is_override_allowed = null
    ): void {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }

        $this->seam->client->post("/thermostats/schedules/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
