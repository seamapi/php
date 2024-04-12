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
            client_session_id: $json->client_session_id,
            connect_webview_ids: $json->connect_webview_ids,
            connected_account_ids: $json->connected_account_ids,
            created_at: $json->created_at,
            device_count: $json->device_count,
            token: $json->token,
            user_identifier_key: $json->user_identifier_key ?? null,
            user_identity_ids: $json->user_identity_ids,
            workspace_id: $json->workspace_id,
        );
    }
  

    
    public function __construct(
        public string $client_session_id,
        public array $connect_webview_ids,
        public array $connected_account_ids,
        public string $created_at,
        public int $device_count,
        public string $token,
        public string | null $user_identifier_key,
        public array $user_identity_ids,
        public string $workspace_id,
    ) {
    }
  
}
