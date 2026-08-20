<?php

declare(strict_types=1);

namespace Tests;

use Seam\InvalidOptionsError;
use Seam\Options;
use Seam\Seam;
use Seam\SeamWithoutWorkspace;
use Tests\Support\FakeSeamConnectTestCase;

final class EnvTest extends FakeSeamConnectTestCase
{
    private const VARIABLES = [
        "SEAM_API_KEY",
        "SEAM_PERSONAL_ACCESS_TOKEN",
        "SEAM_WORKSPACE_ID",
        "SEAM_ENDPOINT",
    ];

    /** @var array<string, string|false> */
    private array $saved_env = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (self::VARIABLES as $name) {
            $this->saved_env[$name] = getenv($name);
            putenv($name);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->saved_env as $name => $value) {
            if ($value === false) {
                putenv($name);
            } else {
                putenv("{$name}={$value}");
            }
        }

        parent::tearDown();
    }

    public function testReadsTheApiKeyFromTheEnvironment(): void
    {
        putenv("SEAM_API_KEY=" . $this->seed["seam_apikey1_token"]);

        $seam = new Seam(endpoint: $this->endpoint);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testReadsTheEndpointFromTheEnvironment(): void
    {
        putenv("SEAM_ENDPOINT=" . $this->endpoint);

        $seam = new Seam(api_key: $this->seed["seam_apikey1_token"]);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testFallsBackToTheDefaultEndpoint(): void
    {
        $this->assertSame(Options::DEFAULT_ENDPOINT, Options::get_endpoint());
    }

    public function testEndpointOptionWinsOverTheEnvironment(): void
    {
        putenv("SEAM_ENDPOINT=https://from-the-environment.example.com");

        $this->assertSame(
            "https://from-the-option.example.com",
            Options::get_endpoint("https://from-the-option.example.com"),
        );
    }

    public function testReadsThePersonalAccessTokenAndWorkspaceIdFromTheEnvironment(): void
    {
        putenv("SEAM_PERSONAL_ACCESS_TOKEN=" . $this->seed["seam_at1_token"]);
        putenv("SEAM_WORKSPACE_ID=" . $this->seed["seed_workspace_1"]);

        $seam = new Seam(endpoint: $this->endpoint);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testReadsOnlyTheWorkspaceIdFromTheEnvironment(): void
    {
        putenv("SEAM_WORKSPACE_ID=" . $this->seed["seed_workspace_1"]);

        $seam = new Seam(
            personal_access_token: $this->seed["seam_at1_token"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testWorkspaceIdOptionWinsOverTheEnvironment(): void
    {
        putenv("SEAM_WORKSPACE_ID=workspace-from-the-environment");

        $seam = new Seam(
            personal_access_token: $this->seed["seam_at1_token"],
            workspace_id: $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    /**
     * Two credentials in the environment are ambiguous, so neither is picked.
     */
    public function testFailsWhenBothCredentialEnvironmentVariablesAreSet(): void
    {
        putenv("SEAM_API_KEY=" . $this->seed["seam_apikey1_token"]);
        putenv("SEAM_PERSONAL_ACCESS_TOKEN=" . $this->seed["seam_at1_token"]);
        putenv("SEAM_WORKSPACE_ID=" . $this->seed["seed_workspace_1"]);

        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage(
            "Both SEAM_API_KEY and SEAM_PERSONAL_ACCESS_TOKEN",
        );

        new Seam(endpoint: $this->endpoint);
    }

    public function testFailsWhenNoCredentialsAreAvailable(): void
    {
        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage("SEAM_API_KEY");

        new Seam(endpoint: $this->endpoint);
    }

    public function testApiKeyEnvironmentVariableIsIgnoredForAPersonalAccessToken(): void
    {
        putenv("SEAM_API_KEY=" . $this->seed["seam_apikey1_token"]);

        $seam = new Seam(
            personal_access_token: $this->seed["seam_at1_token"],
            workspace_id: $this->seed["seed_workspace_1"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testPersonalAccessTokenEnvironmentVariableIsIgnoredForAnApiKey(): void
    {
        putenv("SEAM_PERSONAL_ACCESS_TOKEN=" . $this->seed["seam_at1_token"]);

        $seam = new Seam(
            api_key: $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testWithoutWorkspaceReadsThePersonalAccessTokenFromTheEnvironment(): void
    {
        putenv("SEAM_PERSONAL_ACCESS_TOKEN=" . $this->seed["seam_at1_token"]);

        $seam = new SeamWithoutWorkspace(endpoint: $this->endpoint);

        $workspaces = $seam->workspaces->list();

        $this->assertNotEmpty($workspaces);
    }

    public function testWithoutWorkspaceFailsWhenNoTokenIsAvailable(): void
    {
        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage("SEAM_PERSONAL_ACCESS_TOKEN is not set");

        new SeamWithoutWorkspace(endpoint: $this->endpoint);
    }
}
