<?php

namespace Seam;

use Seam\Resources\Workspace;
use Seam\Routes\WorkspacesClient;

/**
 * Exposes only the workspace endpoints that work without a workspace in
 * scope, which are the only ones a SeamWithoutWorkspace client can call.
 */
class WorkspacesProxy
{
    private WorkspacesClient $workspaces;

    public function __construct(WorkspacesClient $workspaces)
    {
        $this->workspaces = $workspaces;
    }

    /**
     * @return Workspace[]
     */
    public function list(): array
    {
        return $this->workspaces->list();
    }

    /**
     * Creates a new [workspace](https://docs.seam.co/core-concepts/workspaces).
     *
     * Mirrors WorkspacesClient::create, including the types: narrowing a
     * parameter here would reject a value the generated client accepts.
     *
     * @param string $name Name of the new workspace.
     * @param string $company_name Company name for the new workspace.
     * @param string|NullValue $connect_partner_name Connect partner name for the new workspace.
     * @param mixed $connect_webview_customization [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) customizations for the new workspace.
     * @param bool $is_sandbox Indicates whether the new workspace is a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     * @param string $organization_id ID of the organization to associate with the new workspace.
     * @return Workspace OK
     */
    public function create(
        string $name,
        ?string $company_name = null,
        string|NullValue|null $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_sandbox = null,
        ?string $organization_id = null,
        ?string $webview_logo_shape = null,
        ?string $webview_primary_button_color = null,
        ?string $webview_primary_button_text_color = null,
        ?string $webview_success_message = null,
    ): Workspace {
        // Forwarded by name: the generated parameter order follows the API
        // definition, so a positional call could silently shift after a
        // regeneration.
        return $this->workspaces->create(
            name: $name,
            company_name: $company_name,
            connect_partner_name: $connect_partner_name,
            connect_webview_customization: $connect_webview_customization,
            is_sandbox: $is_sandbox,
            organization_id: $organization_id,
            webview_logo_shape: $webview_logo_shape,
            webview_primary_button_color: $webview_primary_button_color,
            webview_primary_button_text_color: $webview_primary_button_text_color,
            webview_success_message: $webview_success_message,
        );
    }
}
