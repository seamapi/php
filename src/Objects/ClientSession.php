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
            user_identifier_key: $json->user_identifier_key ?? null,
            created_at: $json->created_at,
            token: $json->token,
            device_count: $json->device_count,
            connected_account_ids: $json->connected_account_ids,
            connect_webview_ids: $json->connect_webview_ids,
            user_identity_ids: $json->user_identity_ids,
            workspace_id: $json->workspace_id,
        );
    }
  

    
    public function __construct(
        public string $client_session_id,
        public string | null $user_identifier_key,
        public string $created_at,
        public string $token,
        public float $device_count,
        public array $connected_account_ids,
        public array $connect_webview_ids,
        public array $user_identity_ids,
        public string $workspace_id,
    ) {
    }
  
}
