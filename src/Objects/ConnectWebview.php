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
            connected_account_id: $json->connected_account_id ?? null,
            url: $json->url,
            workspace_id: $json->workspace_id,
            device_selection_mode: $json->device_selection_mode,
            accepted_providers: $json->accepted_providers,
            accepted_devices: $json->accepted_devices,
            any_provider_allowed: $json->any_provider_allowed,
            any_device_allowed: $json->any_device_allowed,
            created_at: $json->created_at,
            login_successful: $json->login_successful,
            status: $json->status,
            custom_redirect_url: $json->custom_redirect_url ?? null,
            custom_redirect_failure_url: $json->custom_redirect_failure_url ?? null,
            custom_metadata: $json->custom_metadata,
            automatically_manage_new_devices: $json->automatically_manage_new_devices,
            wait_for_device_creation: $json->wait_for_device_creation,
            authorized_at: $json->authorized_at ?? null,
            selected_provider: $json->selected_provider ?? null,
        );
    }
  

    
    public function __construct(
        public string $connect_webview_id,
        public string | null $connected_account_id,
        public string $url,
        public string $workspace_id,
        public string $device_selection_mode,
        public array $accepted_providers,
        public array $accepted_devices,
        public bool $any_provider_allowed,
        public bool $any_device_allowed,
        public string $created_at,
        public bool $login_successful,
        public string $status,
        public string | null $custom_redirect_url,
        public string | null $custom_redirect_failure_url,
        public mixed $custom_metadata,
        public bool $automatically_manage_new_devices,
        public bool $wait_for_device_creation,
        public string | null $authorized_at,
        public string | null $selected_provider,
    ) {
    }
  
}
