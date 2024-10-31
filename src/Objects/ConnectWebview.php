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
            accepted_devices: $json->accepted_devices,
            accepted_providers: $json->accepted_providers,
            any_device_allowed: $json->any_device_allowed,
            any_provider_allowed: $json->any_provider_allowed,
            automatically_manage_new_devices: $json->automatically_manage_new_devices,
            connect_webview_id: $json->connect_webview_id,
            created_at: $json->created_at,
            custom_metadata: $json->custom_metadata,
            device_selection_mode: $json->device_selection_mode,
            login_successful: $json->login_successful,
            status: $json->status,
            url: $json->url,
            wait_for_device_creation: $json->wait_for_device_creation,
            workspace_id: $json->workspace_id,
            authorized_at: $json->authorized_at ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            custom_redirect_failure_url: $json->custom_redirect_failure_url ??
                null,
            custom_redirect_url: $json->custom_redirect_url ?? null,
            selected_provider: $json->selected_provider ?? null
        );
    }

    public function __construct(
        public array $accepted_devices,
        public array $accepted_providers,
        public bool $any_device_allowed,
        public bool $any_provider_allowed,
        public bool $automatically_manage_new_devices,
        public string $connect_webview_id,
        public string $created_at,
        public mixed $custom_metadata,
        public string $device_selection_mode,
        public bool $login_successful,
        public string $status,
        public string $url,
        public bool $wait_for_device_creation,
        public string $workspace_id,
        public string|null $authorized_at,
        public string|null $connected_account_id,
        public string|null $custom_redirect_failure_url,
        public string|null $custom_redirect_url,
        public string|null $selected_provider
    ) {
    }
}
