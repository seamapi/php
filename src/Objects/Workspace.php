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
            company_name: $json->company_name,
            connect_webview_customization: WorkspaceConnectWebviewCustomization::from_json(
                $json->connect_webview_customization,
            ),
            is_publishable_key_auth_enabled: $json->is_publishable_key_auth_enabled,
            is_sandbox: $json->is_sandbox,
            is_suspended: $json->is_suspended,
            name: $json->name,
            workspace_id: $json->workspace_id,
            publishable_key: $json->publishable_key ?? null,
            connect_partner_name: $json->connect_partner_name ?? null,
        );
    }

    public function __construct(
        public string $company_name,
        public WorkspaceConnectWebviewCustomization $connect_webview_customization,
        public bool $is_publishable_key_auth_enabled,
        public bool $is_sandbox,
        public bool $is_suspended,
        public string $name,
        public string $workspace_id,
        public string|null $publishable_key,
        public string|null $connect_partner_name,
    ) {}
}
