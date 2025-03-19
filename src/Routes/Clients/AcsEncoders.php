<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ActionAttempt;
use Seam\Routes\Objects\AcsEncoder;

class AcsEncoders
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function encode_credential(
        string $acs_encoder_id,
        string $acs_credential_id,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->client->post("/acs/encoders/encode_credential", [
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
        ?string $acs_system_id = null,
        ?float $limit = null,
        ?array $acs_system_ids = null,
        ?array $acs_encoder_ids = null
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($acs_encoder_ids !== null) {
            $request_payload["acs_encoder_ids"] = $acs_encoder_ids;
        }

        $res = $this->seam->client->post("/acs/encoders/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => AcsEncoder::from_json($r),
            $json->acs_encoders
        );
    }

    public function scan_credential(
        string $acs_encoder_id,
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }

        $res = $this->seam->client->post("/acs/encoders/scan_credential", [
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
}
