<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\NullValue;
use Seam\Routes\WorkspacesClient;
use Seam\SeamWithoutWorkspace;
use Seam\WorkspacesProxy;
use Tests\Support\RecordingClient;

final class WorkspacesProxyTest extends TestCase
{
    /**
     * @dataProvider proxiedMethods
     */
    public function testSignatureMatchesTheGeneratedClient(string $method): void
    {
        $proxied = new \ReflectionMethod(WorkspacesProxy::class, $method);
        $generated = new \ReflectionMethod(WorkspacesClient::class, $method);

        $this->assertSame(
            self::describe($generated),
            self::describe($proxied),
            "WorkspacesProxy::{$method} has drifted from WorkspacesClient::{$method}",
        );
    }

    public static function proxiedMethods(): array
    {
        return [
            "create" => ["create"],
            "list" => ["list"],
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function describe(\ReflectionMethod $method): array
    {
        $described = [
            "@return" => (string) $method->getReturnType(),
        ];

        foreach ($method->getParameters() as $parameter) {
            $described[$parameter->getName()] = sprintf(
                "%s%s",
                (string) $parameter->getType(),
                $parameter->isDefaultValueAvailable() ? " = default" : "",
            );
        }

        return $described;
    }

    public function testCreateForwardsTheNullSentinel(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, [
                "workspace" => [
                    "workspace_id" => "ws_1",
                    "name" => "Sentinel Workspace",
                ],
            ]),
        ]);

        $seam = SeamWithoutWorkspace::from_personal_access_token(
            "seam_at_token",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
        );

        $workspace = $seam->workspaces->create(
            name: "Sentinel Workspace",
            connect_partner_name: NullValue::NULL,
        );

        $this->assertSame("Sentinel Workspace", $workspace->name);

        $body = $recorder->body();

        $this->assertSame("Sentinel Workspace", $body->name);
        $this->assertNull($body->connect_partner_name);
        $this->assertTrue(property_exists($body, "connect_partner_name"));
    }
}
