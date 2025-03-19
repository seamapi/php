<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\Workspace;
use Seam\Routes\Objects\ActionAttempt;

class Workspaces
{
    private Seam $seam;

    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $name,
        ?string $company_name = null,
        ?string $connect_partner_name = null,
        ?bool $is_sandbox = null,
        ?string $webview_primary_button_color = null,
        ?string $webview_primary_button_text_color = null,
        ?string $webview_logo_shape = null
    ): Workspace {
        $request_payload = [];

        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($company_name !== null) {
            $request_payload["company_name"] = $company_name;
        }
        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($is_sandbox !== null) {
            $request_payload["is_sandbox"] = $is_sandbox;
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
        if ($webview_logo_shape !== null) {
            $request_payload["webview_logo_shape"] = $webview_logo_shape;
        }

        $res = $this->seam->client->post("/workspaces/create", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Workspace::from_json($json->workspace);
    }

    public function get(): Workspace
    {
        $request_payload = [];

        $res = $this->seam->client->post("/workspaces/get", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return Workspace::from_json($json->workspace);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->client->post("/workspaces/list", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        return array_map(fn($r) => Workspace::from_json($r), $json->workspaces);
    }

    public function reset_sandbox(
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        $res = $this->seam->client->post("/workspaces/reset_sandbox", [
            "json" => (object) $request_payload,
        ]);
        $json = json_decode($res->getBody());

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($json->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $json->action_attempt->action_attempt_id
        );

        return $action_attempt;
    }
}
