<?php

namespace Seam\Objects;

class ClientSession
{
    public static function from_json(mixed $json): ClientSession|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            client_session_id: $json->client_session_id ?? null,
            user_identifier_key: $json->user_identifier_key ?? null,
            created_at: $json->created_at ?? null,
            token: $json->token ?? null,
            device_count: $json->device_count ?? null,
            connected_account_ids: $json->connected_account_ids ?? null,
            connect_webview_ids: $json->connect_webview_ids ?? null,
            user_identity_ids: $json->user_identity_ids ?? null,
            workspace_id: $json->workspace_id ?? null
        );
    }

    public function __construct(
        public string|null $client_session_id,
        public string|null $user_identifier_key,
        public string|null $created_at,
        public string|null $token,
        public float|null $device_count,
        public array|null $connected_account_ids,
        public array|null $connect_webview_ids,
        public array|null $user_identity_ids,
        public string|null $workspace_id
    ) {
    }
}
