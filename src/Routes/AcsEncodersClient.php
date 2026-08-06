<?php

namespace Seam\Routes;

use Seam\Http\ResolveActionAttempt;
use Seam\Http\SeamHttpClient;
use Seam\Resources\AcsEncoder;
use Seam\Resources\ActionAttempt;

class AcsEncodersClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public AcsEncodersSimulateClient $simulate;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new AcsEncodersSimulateClient($client, $defaults);
    }

    /**
     * Encodes an existing [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) onto a plastic card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners). Either provide an `acs_credential_id` or an `access_method_id`
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` to use to encode the `acs_credential`.
     * @param string $access_method_id ID of the `access_method` to encode onto a card.
     * @param string $acs_credential_id ID of the `acs_credential` to encode onto a card.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function encode_credential(
        string $acs_encoder_id,
        ?string $access_method_id = null,
        ?string $acs_credential_id = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->client->request(
            "POST",
            "/acs/encoders/encode_credential",
            json: (object) $request_payload,
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Returns a specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_encoder_id ID of the encoder that you want to get.
     * @return AcsEncoder OK
     */
    public function get(string $acs_encoder_id): AcsEncoder
    {
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;

        $res = $this->client->request(
            "POST",
            "/acs/encoders/get",
            json: (object) $request_payload,
        );

        return AcsEncoder::from_json($res->acs_encoder);
    }

    /**
     * Returns a list of all [encoders](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all encoders.
     * @param array $acs_system_ids IDs of the access systems for which you want to retrieve all encoders.
     * @param array $acs_encoder_ids IDs of the encoders that you want to retrieve.
     * @param float $limit Number of encoders to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
     * @return array OK
     */
    public function list(
        ?string $acs_system_id = null,
        ?array $acs_system_ids = null,
        ?array $acs_encoder_ids = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($acs_encoder_ids !== null) {
            $request_payload["acs_encoder_ids"] = $acs_encoder_ids;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }

        $res = $this->client->request(
            "POST",
            "/acs/encoders/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsEncoder::from_json($r),
            $res->acs_encoders,
        );
    }

    /**
     * Scans an encoded [acs_credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) from a plastic card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_encoder_id ID of the encoder to use for the scan.
     * @param mixed $salto_ks_metadata Salto KS-specific metadata for the scan action.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function scan_credential(
        string $acs_encoder_id,
        mixed $salto_ks_metadata = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($salto_ks_metadata !== null) {
            $request_payload["salto_ks_metadata"] = $salto_ks_metadata;
        }

        $res = $this->client->request(
            "POST",
            "/acs/encoders/scan_credential",
            json: (object) $request_payload,
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Scans a physical card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) and assigns the scanned credential to an ACS user. Provide either an `acs_user_id` or a `user_identity_id`.
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` to use to scan the credential.
     * @param string $acs_user_id ID of the `acs_user` to assign the scanned credential to.
     * @param mixed $salto_ks_metadata Salto KS-specific metadata for the scan action.
     * @param string $user_identity_id ID of the `user_identity` to assign the scanned credential to. If the ACS system contains an ACS user linked to this user identity, it is used. Otherwise, one is created.
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function scan_to_assign_credential(
        string $acs_encoder_id,
        ?string $acs_user_id = null,
        mixed $salto_ks_metadata = null,
        ?string $user_identity_id = null,
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $request_payload = [];

        $request_payload["acs_encoder_id"] = $acs_encoder_id;
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($salto_ks_metadata !== null) {
            $request_payload["salto_ks_metadata"] = $salto_ks_metadata;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->client->request(
            "POST",
            "/acs/encoders/scan_to_assign_credential",
            json: (object) $request_payload,
        );

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }
}
