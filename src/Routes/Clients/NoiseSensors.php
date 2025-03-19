<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Device;

class NoiseSensors
{
    private Seam $seam;
    public NoiseSensorsNoiseThresholds $noise_thresholds;
    public NoiseSensorsSimulate $simulate;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->noise_thresholds = new NoiseSensorsNoiseThresholds($seam);
        $this->simulate = new NoiseSensorsSimulate($seam);
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

        $res = $this->seam->client->post("/noise_sensors/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Device::from_json($r), $json->devices);
    }
}
