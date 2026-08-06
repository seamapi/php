<?php

declare(strict_types=1);

namespace Tests;

use Seam\Exceptions\InvalidOptionsError;
use Seam\Options;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;

final class EnvTest extends FakeSeamConnectTestCase
{
    private const VARIABLES = ["SEAM_API_KEY", "SEAM_ENDPOINT", "SEAM_API_URL"];

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

    public function testSeamEndpointWinsOverTheDeprecatedSeamApiUrl(): void
    {
        putenv("SEAM_ENDPOINT=https://endpoint.example.com");
        putenv("SEAM_API_URL=https://api-url.example.com");

        // Both the deprecation and the precedence notice are raised.
        $endpoint = @Options::get_endpoint();

        $this->assertSame("https://endpoint.example.com", $endpoint);
    }

    public function testDeprecatedSeamApiUrlIsStillHonored(): void
    {
        putenv("SEAM_API_URL=https://api-url.example.com");

        $this->assertSame(
            "https://api-url.example.com",
            @Options::get_endpoint(),
        );
    }

    public function testDeprecatedSeamApiUrlWarns(): void
    {
        putenv("SEAM_API_URL=https://api-url.example.com");

        set_error_handler(
            static fn(
                int $severity,
                string $message,
            ) => throw new \ErrorException($message),
            E_USER_WARNING,
        );

        try {
            $this->expectException(\ErrorException::class);
            $this->expectExceptionMessage("SEAM_API_URL");
            Options::get_endpoint();
        } finally {
            restore_error_handler();
        }
    }

    public function testFailsWhenNoCredentialsAreAvailable(): void
    {
        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage("SEAM_API_KEY is not set");

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
}
