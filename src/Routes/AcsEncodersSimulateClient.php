<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;

class AcsEncodersSimulateClient
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
     * Simulates that the next attempt to encode a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will fail. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to encode the `acs_credential`.
     * @param string $error_code Code of the error to simulate.
     * @param string $acs_credential_id ID of the `acs_credential` that will fail to be encoded onto a card in the next request.
     * @return void OK
     */
    public function next_credential_encode_will_fail(
        ?string $acs_encoder_id = null,
        ?string $error_code = null,
        ?string $acs_credential_id = null,
    ): void {
        if ($acs_encoder_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/encoders/simulate/next_credential_encode_will_fail",
            );
        }
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $this->client->request(
            "POST",
            "/acs/encoders/simulate/next_credential_encode_will_fail",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Simulates that the next attempt to encode a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will succeed. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to encode the `acs_credential`.
     * @param string $scenario Scenario to simulate.
     * @return void OK
     */
    public function next_credential_encode_will_succeed(
        ?string $acs_encoder_id = null,
        ?string $scenario = null,
    ): void {
        if ($acs_encoder_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/encoders/simulate/next_credential_encode_will_succeed",
            );
        }
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }

        $this->client->request(
            "POST",
            "/acs/encoders/simulate/next_credential_encode_will_succeed",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Simulates that the next attempt to scan a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will fail. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will fail to scan the `acs_credential` in the next request.
     * @param string $error_code
     * @param string $acs_credential_id_on_seam
     * @return void OK
     */
    public function next_credential_scan_will_fail(
        ?string $acs_encoder_id = null,
        ?string $error_code = null,
        ?string $acs_credential_id_on_seam = null,
    ): void {
        if ($acs_encoder_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/encoders/simulate/next_credential_scan_will_fail",
            );
        }
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }

        $this->client->request(
            "POST",
            "/acs/encoders/simulate/next_credential_scan_will_fail",
            ["json" => (object) $request_payload],
        );
    }

    /**
     * Simulates that the next attempt to scan a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will succeed. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to scan the `acs_credential`.
     * @param string $acs_credential_id_on_seam ID of the Seam `acs_credential` that matches the `acs_credential` on the encoder in this simulation.
     * @param string $scenario Scenario to simulate.
     * @return void OK
     */
    public function next_credential_scan_will_succeed(
        ?string $acs_encoder_id = null,
        ?string $acs_credential_id_on_seam = null,
        ?string $scenario = null,
    ): void {
        if ($acs_encoder_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /acs/encoders/simulate/next_credential_scan_will_succeed",
            );
        }
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }

        $this->client->request(
            "POST",
            "/acs/encoders/simulate/next_credential_scan_will_succeed",
            ["json" => (object) $request_payload],
        );
    }
}
