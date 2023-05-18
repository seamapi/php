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
            connect_webview_ids: $json->connect_webview_ids ?? null,
            connected_account_ids: $json->connected_account_ids?? null,
            token: $json->token ?? null,
        );
    }

    public function __construct(
        public string $client_session_id,

        public string | null $user_identifier_key,
        public mixed $connect_webview_ids,
        public mixed $connected_account_ids,
        public string $token,
    ) {
    }
}
