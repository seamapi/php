<?php

namespace Seam\Objects;

class ConnectWebview
{
    public static function from_json(mixed $json): ConnectWebview|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            connect_webview_id: $json->connect_webview_id,
            workspace_id: $json->workspace_id,
            url: $json->url,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at,
            status: $json->status,
            custom_redirect_url: $json->custom_redirect_url ?? null,
            error: SeamError::from_json($json->error ?? null)
        );
    }

    public function __construct(
        public string $connect_webview_id,
        public string $workspace_id,
        public string $url,

        public string|null $connected_account_id,
        /* Can be "pending", "authorized" or "error" */
        public string $status,
        public string|null $custom_redirect_url,
        public string $created_at,
        public SeamError|null $error
    ) {
    }
}
