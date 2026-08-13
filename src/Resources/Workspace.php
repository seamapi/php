<?php

namespace Seam\Resources {
    /**
     * Represents a Seam [workspace](https://docs.seam.co/core-concepts/workspaces). A workspace is a top-level entity that encompasses all other resources below it, such as devices, connected accounts, and Connect Webviews. Seam provides two types of workspaces. A [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces) is a special type of workspace designed for testing code. Sandbox workspaces offer test device accounts and virtual devices that you can connect and control. This ability to work with virtual devices is quite handy because it removes the need to own physical devices from multiple brands. To connect real devices and systems to Seam, use a [production workspace](https://docs.seam.co/core-concepts/workspaces#production-workspaces).
     */
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
                    ? Workspace\ConnectWebviewCustomization::from_json(
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
            /**
             * Company name associated with the [workspace](https://docs.seam.co/core-concepts/workspaces).
             */
            public string|null $company_name,
            /**
             * @deprecated Use `company_name` instead.
             */
            public string|null $connect_partner_name,
            public Workspace\ConnectWebviewCustomization|null $connect_webview_customization,
            /**
             * Indicates whether publishable key authentication is enabled for this workspace.
             */
            public bool|null $is_publishable_key_auth_enabled,
            /**
             * Indicates whether the workspace is a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
             */
            public bool|null $is_sandbox,
            /**
             * Indicates whether the [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces) is suspended. Seam suspends sandbox workspaces that have not been accessed in 14 days.
             */
            public bool|null $is_suspended,
            /**
             * Name of the [workspace](https://docs.seam.co/core-concepts/workspaces).
             */
            public string|null $name,
            /**
             * ID of the organization to which the workspace belongs, or `null` if the workspace is not assigned to an organization.
             */
            public string|null $organization_id,
            /**
             * Publishable key for the [workspace](https://docs.seam.co/core-concepts/workspaces). This key is used to identify the workspace in client-side applications.
             */
            public string|null $publishable_key,
            /**
             * ID of the workspace.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\Workspace {
    class ConnectWebviewCustomization
    {
        public static function from_json(
            mixed $json,
        ): ConnectWebviewCustomization|null {
            if (!$json) {
                return null;
            }
            return new self(
                inviter_logo_url: $json->inviter_logo_url ?? null,
                logo_shape: $json->logo_shape ?? null,
                primary_button_color: $json->primary_button_color ?? null,
                primary_button_text_color: $json->primary_button_text_color ??
                    null,
                success_message: $json->success_message ?? null,
            );
        }

        public function __construct(
            /**
             * URL of the inviter logo for [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) in the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
             */
            public string|null $inviter_logo_url,
            /**
             * Logo shape for [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) in the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
             */
            public string|null $logo_shape,
            /**
             * Primary button color for [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) in the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
             */
            public string|null $primary_button_color,
            /**
             * Primary button text color for [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) in the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
             */
            public string|null $primary_button_text_color,
            /**
             * Success message for [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) in the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
             */
            public string|null $success_message,
        ) {}
    }
}
