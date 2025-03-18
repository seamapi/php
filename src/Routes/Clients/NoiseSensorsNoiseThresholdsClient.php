<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\NoiseThreshold;

class NoiseSensorsNoiseThresholdsClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create(
    string $device_id,
    string $ends_daily_at,
    string $starts_daily_at,
    string $name = null,
    float $noise_threshold_decibels = null,
    float $noise_threshold_nrs = null,
    bool $sync = null
  ): NoiseThreshold {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($ends_daily_at !== null) {
      $request_payload["ends_daily_at"] = $ends_daily_at;
    }
    if ($starts_daily_at !== null) {
      $request_payload["starts_daily_at"] = $starts_daily_at;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($noise_threshold_decibels !== null) {
      $request_payload["noise_threshold_decibels"] = $noise_threshold_decibels;
    }
    if ($noise_threshold_nrs !== null) {
      $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $res = $this->seam->request(
      "POST",
      "/noise_sensors/noise_thresholds/create",
      json: (object) $request_payload,
      inner_object: "noise_threshold"
    );

    return NoiseThreshold::from_json($res);
  }

  public function delete(
    string $device_id,
    string $noise_threshold_id,
    bool $sync = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($noise_threshold_id !== null) {
      $request_payload["noise_threshold_id"] = $noise_threshold_id;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $this->seam->request(
      "POST",
      "/noise_sensors/noise_thresholds/delete",
      json: (object) $request_payload
    );
  }

  public function get(string $noise_threshold_id): NoiseThreshold
  {
    $request_payload = [];

    if ($noise_threshold_id !== null) {
      $request_payload["noise_threshold_id"] = $noise_threshold_id;
    }

    $res = $this->seam->request(
      "POST",
      "/noise_sensors/noise_thresholds/get",
      json: (object) $request_payload,
      inner_object: "noise_threshold"
    );

    return NoiseThreshold::from_json($res);
  }

  public function list(string $device_id, bool $is_programmed = null): array
  {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($is_programmed !== null) {
      $request_payload["is_programmed"] = $is_programmed;
    }

    $res = $this->seam->request(
      "POST",
      "/noise_sensors/noise_thresholds/list",
      json: (object) $request_payload,
      inner_object: "noise_thresholds"
    );

    return array_map(fn($r) => NoiseThreshold::from_json($r), $res);
  }

  public function update(
    string $device_id,
    string $noise_threshold_id,
    string $ends_daily_at = null,
    string $name = null,
    float $noise_threshold_decibels = null,
    float $noise_threshold_nrs = null,
    string $starts_daily_at = null,
    bool $sync = null
  ): void {
    $request_payload = [];

    if ($device_id !== null) {
      $request_payload["device_id"] = $device_id;
    }
    if ($noise_threshold_id !== null) {
      $request_payload["noise_threshold_id"] = $noise_threshold_id;
    }
    if ($ends_daily_at !== null) {
      $request_payload["ends_daily_at"] = $ends_daily_at;
    }
    if ($name !== null) {
      $request_payload["name"] = $name;
    }
    if ($noise_threshold_decibels !== null) {
      $request_payload["noise_threshold_decibels"] = $noise_threshold_decibels;
    }
    if ($noise_threshold_nrs !== null) {
      $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
    }
    if ($starts_daily_at !== null) {
      $request_payload["starts_daily_at"] = $starts_daily_at;
    }
    if ($sync !== null) {
      $request_payload["sync"] = $sync;
    }

    $this->seam->request(
      "POST",
      "/noise_sensors/noise_thresholds/update",
      json: (object) $request_payload
    );
  }
}
