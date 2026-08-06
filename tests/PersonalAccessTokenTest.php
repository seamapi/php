<?php

declare(strict_types=1);

namespace Tests;

use Seam\Exceptions\InvalidOptionsError;
use Seam\Exceptions\InvalidTokenError;
use Seam\Seam;
use Seam\SeamMultiWorkspace;
use Tests\Support\FakeSeamConnectTestCase;

final class PersonalAccessTokenTest extends FakeSeamConnectTestCase
{
    public function testFromPersonalAccessTokenReturnsAnAuthorizedClient(): void
    {
        $seam = Seam::from_personal_access_token(
            $this->seed["seam_at1_token"],
            $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
        $this->assertSame(
            $this->seed["seed_workspace_1"],
            $device->workspace_id,
        );
    }

    public function testConstructorReturnsAnAuthorizedClient(): void
    {
        $seam = new Seam(
            personal_access_token: $this->seed["seam_at1_token"],
            workspace_id: $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testWorkspaceIdIsRequired(): void
    {
        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage(
            "Must pass a workspace_id when using a personal_access_token",
        );

        new Seam(
            personal_access_token: $this->seed["seam_at1_token"],
            endpoint: $this->endpoint,
        );
    }

    public function testApiKeyCannotBeCombinedWithAPersonalAccessToken(): void
    {
        $this->expectException(InvalidOptionsError::class);

        new Seam(
            api_key: $this->seed["seam_apikey1_token"],
            personal_access_token: $this->seed["seam_at1_token"],
            workspace_id: $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );
    }

    public function testPersonalAccessTokenFormatIsChecked(): void
    {
        $this->expectException(InvalidTokenError::class);

        new Seam(
            personal_access_token: "seam_cst_1234",
            workspace_id: $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );
    }

    public function testMultiWorkspaceClientListsWorkspaces(): void
    {
        $seam = SeamMultiWorkspace::from_personal_access_token(
            $this->seed["seam_at1_token"],
            endpoint: $this->endpoint,
        );

        $workspaces = $seam->workspaces->list();

        $workspace_ids = array_map(
            fn($workspace) => $workspace->workspace_id,
            $workspaces,
        );

        $this->assertContains($this->seed["seed_workspace_1"], $workspace_ids);
    }

    public function testMultiWorkspaceConstructorListsWorkspaces(): void
    {
        $seam = new SeamMultiWorkspace(
            personal_access_token: $this->seed["seam_at1_token"],
            endpoint: $this->endpoint,
        );

        $this->assertNotEmpty($seam->workspaces->list());
    }

    public function testMultiWorkspaceClientCreatesAWorkspace(): void
    {
        $seam = SeamMultiWorkspace::from_personal_access_token(
            $this->seed["seam_at1_token"],
            endpoint: $this->endpoint,
        );

        $workspace = $seam->workspaces->create(
            name: "Test Workspace",
            connect_partner_name: "Test Partner",
            is_sandbox: true,
        );

        $this->assertSame("Test Workspace", $workspace->name);
    }

    public function testMultiWorkspaceClientRequiresAToken(): void
    {
        $this->expectException(InvalidOptionsError::class);

        new SeamMultiWorkspace(endpoint: $this->endpoint);
    }

    public function testMultiWorkspaceClientChecksTheTokenFormat(): void
    {
        $this->expectException(InvalidTokenError::class);

        SeamMultiWorkspace::from_personal_access_token(
            $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
        );
    }
}
