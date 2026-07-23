<?php

namespace Seam\Objects;

class Workspace
{
    public static function from_json(mixed $json): Workspace|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            company_name: $json->company_name ?? null,
            connect_partner_name: $json->connect_partner_name ?? null,
            connect_webview_customization: isset(
                $json->connect_webview_customization,
            )
                ? WorkspaceConnectWebviewCustomization::from_json(
                    $json->connect_webview_customization,
                )
                : null,
            is_publishable_key_auth_enabled: $json->is_publishable_key_auth_enabled ??
                null,
            is_sandbox: $json->is_sandbox ?? null,
            is_suspended: $json->is_suspended ?? null,
            name: $json->name ?? null,
            organization_id: $json->organization_id ?? null,
            publishable_key: $json->publishable_key ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $company_name,
        public string|null $connect_partner_name,
        public WorkspaceConnectWebviewCustomization|null $connect_webview_customization,
        public bool|null $is_publishable_key_auth_enabled,
        public bool|null $is_sandbox,
        public bool|null $is_suspended,
        public string|null $name,
        public string|null $organization_id,
        public string|null $publishable_key,
        public string|null $workspace_id,
    ) {}
}
