<?php

namespace Seam\Resources;

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

class WorkspaceConnectWebviewCustomization
{
    public static function from_json(
        mixed $json,
    ): WorkspaceConnectWebviewCustomization|null {
        if (!$json) {
            return null;
        }
        return new self(
            inviter_logo_url: $json->inviter_logo_url ?? null,
            logo_shape: $json->logo_shape ?? null,
            primary_button_color: $json->primary_button_color ?? null,
            primary_button_text_color: $json->primary_button_text_color ?? null,
            success_message: $json->success_message ?? null,
        );
    }

    public function __construct(
        public string|null $inviter_logo_url,
        public string|null $logo_shape,
        public string|null $primary_button_color,
        public string|null $primary_button_text_color,
        public string|null $success_message,
    ) {}
}
