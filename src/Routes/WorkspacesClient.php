<?php

namespace Seam\Routes;

use Seam\Http\ResolveActionAttempt;
use Seam\Http\SeamHttpClient;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Workspace;

class WorkspacesClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Creates a new [workspace](https://docs.seam.co/core-concepts/workspaces).
     *
     * @param string $name Name of the new workspace.
     * @param string $company_name Company name for the new workspace.
     * @param string $connect_partner_name Connect partner name for the new workspace.
     * @param mixed $connect_webview_customization [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) customizations for the new workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
     * @param bool $is_sandbox Indicates whether the new workspace is a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     * @param string $organization_id ID of the organization to associate with the new workspace.
     * @param string $webview_logo_shape
     * @param string $webview_primary_button_color
     * @param string $webview_primary_button_text_color
     * @param string $webview_success_message
     * @return Workspace OK
     */
    public function create(
        string $name,
        ?string $company_name = null,
        ?string $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_sandbox = null,
        ?string $organization_id = null,
        ?string $webview_logo_shape = null,
        ?string $webview_primary_button_color = null,
        ?string $webview_primary_button_text_color = null,
        ?string $webview_success_message = null,
    ): Workspace {
        $request_payload = [];

        $request_payload["name"] = $name;
        if ($company_name !== null) {
            $request_payload["company_name"] = $company_name;
        }
        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($connect_webview_customization !== null) {
            $request_payload[
                "connect_webview_customization"
            ] = $connect_webview_customization;
        }
        if ($is_sandbox !== null) {
            $request_payload["is_sandbox"] = $is_sandbox;
        }
        if ($organization_id !== null) {
            $request_payload["organization_id"] = $organization_id;
        }
        if ($webview_logo_shape !== null) {
            $request_payload["webview_logo_shape"] = $webview_logo_shape;
        }
        if ($webview_primary_button_color !== null) {
            $request_payload[
                "webview_primary_button_color"
            ] = $webview_primary_button_color;
        }
        if ($webview_primary_button_text_color !== null) {
            $request_payload[
                "webview_primary_button_text_color"
            ] = $webview_primary_button_text_color;
        }
        if ($webview_success_message !== null) {
            $request_payload[
                "webview_success_message"
            ] = $webview_success_message;
        }

        $res = $this->client->request(
            "POST",
            "/workspaces/create",
            json: (object) $request_payload,
        );

        return Workspace::from_json($res->workspace);
    }

    /**
     * Returns the [workspace](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @return Workspace OK
     */
    public function get(): Workspace
    {
        $res = $this->client->request("POST", "/workspaces/get");

        return Workspace::from_json($res->workspace);
    }

    /**
     * Returns a list of [workspaces](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @return array OK
     */
    public function list(): array
    {
        $res = $this->client->request("POST", "/workspaces/list");

        return array_map(fn($r) => Workspace::from_json($r), $res->workspaces);
    }

    /**
     * Resets the [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces) associated with the authentication value. Note that this endpoint is only available for sandbox workspaces.
     *
     * @param bool|array|null $wait_for_action_attempt Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.
     * @return ActionAttempt OK
     */
    public function reset_sandbox(
        bool|array|null $wait_for_action_attempt = null,
    ): ActionAttempt {
        $res = $this->client->request("POST", "/workspaces/reset_sandbox");

        return ResolveActionAttempt::resolve_action_attempt(
            ActionAttempt::from_json($res->action_attempt),
            $this->client,
            $wait_for_action_attempt ??
                $this->defaults["wait_for_action_attempt"],
        );
    }

    /**
     * Updates the [workspace](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @param string $connect_partner_name Connect partner name for the workspace.
     * @param mixed $connect_webview_customization [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) customizations for the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
     * @param bool $is_publishable_key_auth_enabled Indicates whether publishable key authentication is enabled for this workspace.
     * @param bool $is_suspended Indicates whether the workspace is suspended.
     * @param string $name Name of the workspace.
     * @param string $organization_id ID of the organization to assign the workspace to. The authenticated user must be the owner of the workspace and an admin of the target organization.
     * @return void OK
     */
    public function update(
        ?string $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_publishable_key_auth_enabled = null,
        ?bool $is_suspended = null,
        ?string $name = null,
        ?string $organization_id = null,
    ): void {
        $request_payload = [];

        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($connect_webview_customization !== null) {
            $request_payload[
                "connect_webview_customization"
            ] = $connect_webview_customization;
        }
        if ($is_publishable_key_auth_enabled !== null) {
            $request_payload[
                "is_publishable_key_auth_enabled"
            ] = $is_publishable_key_auth_enabled;
        }
        if ($is_suspended !== null) {
            $request_payload["is_suspended"] = $is_suspended;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($organization_id !== null) {
            $request_payload["organization_id"] = $organization_id;
        }

        $this->client->request(
            "POST",
            "/workspaces/update",
            json: (object) $request_payload,
        );
    }
}
