<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ActionAttempt;
use Seam\Routes\Objects\Device;

class Thermostats
{
    private Seam $seam;
    public ThermostatsSchedules $schedules;
    public ThermostatsSimulate $simulate;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->schedules = new ThermostatsSchedules($seam);
        $this->simulate = new ThermostatsSimulate($seam);
    }

    public function activate_climate_preset(
        string $device_id,
        string $climate_preset_key,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }

        $res = $this->seam->client->post(
            "/thermostats/activate_climate_preset",
            ["json" => (object) $request_payload]
        );
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->client->post("/thermostats/cool", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function create_climate_preset(
        string $device_id,
        string $climate_preset_key,
        ?bool $manual_override_allowed = null,
        ?string $name = null,
        ?string $fan_mode_setting = null,
        ?string $hvac_mode_setting = null,
        ?float $cooling_set_point_celsius = null,
        ?float $heating_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_fahrenheit = null
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }

        $this->seam->client->post("/thermostats/create_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    public function delete_climate_preset(
        string $device_id,
        string $climate_preset_key
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }

        $this->seam->client->post("/thermostats/delete_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    public function heat(
        string $device_id,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->client->post("/thermostats/heat", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function heat_cool(
        string $device_id,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->client->post("/thermostats/heat_cool", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function list(
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $connect_webview_id = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?string $manufacturer = null,
        ?array $device_ids = null,
        ?float $limit = null,
        ?string $created_before = null,
        ?string $user_identifier_key = null,
        mixed $custom_metadata_has = null,
        ?array $include_if = null,
        ?array $exclude_if = null,
        ?string $unstable_location_id = null
    ): array {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
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
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }

        $res = $this->seam->client->post("/thermostats/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Device::from_json($r), $json->devices);
    }

    public function off(
        string $device_id,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->client->post("/thermostats/off", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function set_fallback_climate_preset(
        string $device_id,
        string $climate_preset_key
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }

        $this->seam->client->post("/thermostats/set_fallback_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }

    public function set_fan_mode(
        string $device_id,
        ?string $fan_mode = null,
        ?string $fan_mode_setting = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($fan_mode !== null) {
            $request_payload["fan_mode"] = $fan_mode;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->client->post("/thermostats/set_fan_mode", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function set_hvac_mode(
        string $hvac_mode_setting,
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

        $res = $this->seam->client->post("/thermostats/set_hvac_mode", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }

    public function set_temperature_threshold(
        string $device_id,
        ?float $lower_limit_celsius = null,
        ?float $lower_limit_fahrenheit = null,
        ?float $upper_limit_celsius = null,
        ?float $upper_limit_fahrenheit = null
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
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

        $this->seam->client->post("/thermostats/set_temperature_threshold", [
            "json" => (object) $request_payload,
        ]);
    }

    public function update_climate_preset(
        string $device_id,
        string $climate_preset_key,
        bool $manual_override_allowed,
        ?string $name = null,
        ?string $fan_mode_setting = null,
        ?string $hvac_mode_setting = null,
        ?float $cooling_set_point_celsius = null,
        ?float $heating_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_fahrenheit = null
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }

        $this->seam->client->post("/thermostats/update_climate_preset", [
            "json" => (object) $request_payload,
        ]);
    }
}
