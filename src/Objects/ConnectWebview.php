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
            accepted_capabilities: $json->accepted_capabilities ?? null,
            accepted_providers: $json->accepted_providers ?? null,
            any_provider_allowed: $json->any_provider_allowed ?? null,
            authorized_at: $json->authorized_at ?? null,
            automatically_manage_new_devices: $json->automatically_manage_new_devices ??
                null,
            connect_webview_id: $json->connect_webview_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            custom_metadata: $json->custom_metadata ?? null,
            custom_redirect_failure_url: $json->custom_redirect_failure_url ??
                null,
            custom_redirect_url: $json->custom_redirect_url ?? null,
            customer_key: $json->customer_key ?? null,
            device_selection_mode: $json->device_selection_mode ?? null,
            login_successful: $json->login_successful ?? null,
            selected_provider: $json->selected_provider ?? null,
            status: $json->status ?? null,
            url: $json->url ?? null,
            wait_for_device_creation: $json->wait_for_device_creation ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public array|null $accepted_capabilities,
        public array|null $accepted_providers,
        public bool|null $any_provider_allowed,
        public string|null $authorized_at,
        public bool|null $automatically_manage_new_devices,
        public string|null $connect_webview_id,
        public string|null $connected_account_id,
        public string|null $created_at,
        public mixed $custom_metadata,
        public string|null $custom_redirect_failure_url,
        public string|null $custom_redirect_url,
        public string|null $customer_key,
        public string|null $device_selection_mode,
        public bool|null $login_successful,
        public string|null $selected_provider,
        public string|null $status,
        public string|null $url,
        public bool|null $wait_for_device_creation,
        public string|null $workspace_id,
    ) {}
}
