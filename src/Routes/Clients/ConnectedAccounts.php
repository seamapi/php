<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ConnectedAccount;

class ConnectedAccounts
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function delete(
        string $connected_account_id,
        ?bool $sync = null
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->client->post("/connected_accounts/delete", [
            "json" => (object) $request_payload,
        ]);
    }

    public function get(
        ?string $connected_account_id = null,
        ?string $email = null
    ): ConnectedAccount {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }

        $res = $this->seam->client->post("/connected_accounts/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return ConnectedAccount::from_json($json->connected_account);
    }

    public function list(
        ?string $user_identifier_key = null,
        mixed $custom_metadata_has = null
    ): array {
        $request_payload = [];

        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }

        $res = $this->seam->client->post("/connected_accounts/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(
            fn($r) => ConnectedAccount::from_json($r),
            $json->connected_accounts
        );
    }

    public function update(
        string $connected_account_id,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }

        $this->seam->client->post("/connected_accounts/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
