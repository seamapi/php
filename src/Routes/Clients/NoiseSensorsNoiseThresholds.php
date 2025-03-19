<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\NoiseThreshold;

class NoiseSensorsNoiseThresholds
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $device_id,
        string $starts_daily_at,
        string $ends_daily_at,
        ?bool $sync = null,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null
    ): NoiseThreshold {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($starts_daily_at !== null) {
            $request_payload["starts_daily_at"] = $starts_daily_at;
        }
        if ($ends_daily_at !== null) {
            $request_payload["ends_daily_at"] = $ends_daily_at;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
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

        $res = $this->seam->client->post(
            "/noise_sensors/noise_thresholds/create",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return NoiseThreshold::from_json($json->noise_threshold);
    }

    public function delete(
        string $noise_threshold_id,
        string $device_id,
        ?bool $sync = null
    ): void {
        $request_payload = [];

        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->client->post("/noise_sensors/noise_thresholds/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(string $noise_threshold_id): NoiseThreshold
    {
        $request_payload = [];

        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }

        $res = $this->seam->client->post(
            "/noise_sensors/noise_thresholds/get",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return NoiseThreshold::from_json($json->noise_threshold);
    }

    public function list(string $device_id, ?bool $is_programmed = null): array
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($is_programmed !== null) {
            $request_payload["is_programmed"] = $is_programmed;
        }

        $res = $this->seam->client->post(
            "/noise_sensors/noise_thresholds/list",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => NoiseThreshold::from_json($r),
            $json->noise_thresholds
        );
    }

    public function update(
        string $noise_threshold_id,
        string $device_id,
        ?bool $sync = null,
        ?string $name = null,
        ?string $starts_daily_at = null,
        ?string $ends_daily_at = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null
    ): void {
        $request_payload = [];

        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_daily_at !== null) {
            $request_payload["starts_daily_at"] = $starts_daily_at;
        }
        if ($ends_daily_at !== null) {
            $request_payload["ends_daily_at"] = $ends_daily_at;
        }
        if ($noise_threshold_decibels !== null) {
            $request_payload[
                "noise_threshold_decibels"
            ] = $noise_threshold_decibels;
        }
        if ($noise_threshold_nrs !== null) {
            $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
        }

        $this->seam->client->post("/noise_sensors/noise_thresholds/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
