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
            connect_webview_id: $json->connect_webview_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            url: $json->url ?? null,
            workspace_id: $json->workspace_id ?? null,
            device_selection_mode: $json->device_selection_mode ?? null,
            accepted_providers: $json->accepted_providers ?? null,
            accepted_devices: $json->accepted_devices ?? null,
            any_provider_allowed: $json->any_provider_allowed ?? null,
            any_device_allowed: $json->any_device_allowed ?? null,
            created_at: $json->created_at ?? null,
            login_successful: $json->login_successful ?? null,
            status: $json->status ?? null,
            custom_redirect_url: $json->custom_redirect_url ?? null,
            custom_redirect_failure_url: $json->custom_redirect_failure_url ??
                null,
            custom_metadata: $json->custom_metadata ?? null,
            automatically_manage_new_devices: $json->automatically_manage_new_devices ??
                null,
            wait_for_device_creation: $json->wait_for_device_creation ?? null,
            authorized_at: $json->authorized_at ?? null,
            selected_provider: $json->selected_provider ?? null
        );
    }

    public function __construct(
        public string|null $connect_webview_id,
        public string|null $connected_account_id,
        public string|null $url,
        public string|null $workspace_id,
        public string|null $device_selection_mode,
        public array|null $accepted_providers,
        public array|null $accepted_devices,
        public bool|null $any_provider_allowed,
        public bool|null $any_device_allowed,
        public string|null $created_at,
        public bool|null $login_successful,
        public string|null $status,
        public string|null $custom_redirect_url,
        public string|null $custom_redirect_failure_url,
        public mixed $custom_metadata,
        public bool|null $automatically_manage_new_devices,
        public bool|null $wait_for_device_creation,
        public string|null $authorized_at,
        public string|null $selected_provider
    ) {
    }
}
