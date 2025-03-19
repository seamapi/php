<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class AcsEncodersSimulate
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function next_credential_encode_will_fail(
        string $acs_encoder_id,
        ?string $error_code = null,
        ?string $acs_credential_id = null
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $this->seam->client->post(
            "/acs/encoders/simulate/next_credential_encode_will_fail",
            ["json" => (object) $request_payload]
        );
    }

    public function next_credential_encode_will_succeed(
        string $acs_encoder_id,
        ?string $scenario = null
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }

        $this->seam->client->post(
            "/acs/encoders/simulate/next_credential_encode_will_succeed",
            ["json" => (object) $request_payload]
        );
    }

    public function next_credential_scan_will_fail(
        string $acs_encoder_id,
        ?string $error_code = null,
        ?string $acs_credential_id_on_seam = null
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }

        $this->seam->client->post(
            "/acs/encoders/simulate/next_credential_scan_will_fail",
            ["json" => (object) $request_payload]
        );
    }

    public function next_credential_scan_will_succeed(
        string $acs_encoder_id,
        ?string $scenario = null,
        ?string $acs_credential_id_on_seam = null
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }

        $this->seam->client->post(
            "/acs/encoders/simulate/next_credential_scan_will_succeed",
            ["json" => (object) $request_payload]
        );
    }
}
