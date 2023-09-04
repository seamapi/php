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
      custom_redirect_failure_url: $json->custom_redirect_failure_url ?? null,
      device_selection_mode: $json->device_selection_mode ?? null,
      accepted_providers: $json->accepted_providers ?? null,
      accepted_devices: $json->accepted_devices ?? null,
      any_provider_allowed: $json->any_provider_allowed ?? null,
      any_device_allowed: $json->any_device_allowed ?? null,
      login_successful: $json->login_successful ?? null,
      wait_for_device_creation: $json->wait_for_device_creation ?? null,
      automatically_manage_new_devices: $json->automatically_manage_new_devices ?? null,
      error: SeamError::from_json($json->error ?? null)
    );
  }

  public function __construct(
    public string $connect_webview_id,
    public string $workspace_id,
    public string $url,
    public string|null $connected_account_id,
    public string $created_at,

    /* Can be "none", "single" or "multiple" */
    public string|null $device_selection_mode,
    public array|null $accepted_providers,
    public array|null $accepted_devices,
    public bool|null $any_provider_allowed,
    public bool|null $any_device_allowed,
    public bool|null $login_successful,
    public bool|null $wait_for_device_creation,
    public bool|null $automatically_manage_new_devices,

    /* Can be "pending", "authorized" or "error" */
    public string $status,
    public string|null $custom_redirect_url,
    public string|null $custom_redirect_failure_url,
    public SeamError|null $error
  ) {
  }
}
