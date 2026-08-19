<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use Seam\Resources\Device;
use Seam\Resources\Event;
use Seam\Routes\DevicesClient;

final class RecordTypesTest extends TestCase
{
    public function testRecordValueTypesAreGenerated(): void
    {
        $propertyDoc = (new ReflectionProperty(
            Device::class,
            "custom_metadata",
        ))->getDocComment();
        $methodDoc = (new ReflectionMethod(
            DevicesClient::class,
            "update",
        ))->getDocComment();

        $this->assertIsString($propertyDoc);
        $this->assertStringContainsString(
            "@var array<string, string|bool>|\\stdClass|null",
            $propertyDoc,
        );
        $this->assertStringContainsString(
            "@var array<string, string|bool>|\\stdClass|null",
            (string) (new ReflectionProperty(
                Event::class,
                "connected_account_custom_metadata",
            ))->getDocComment(),
        );
        $this->assertStringContainsString(
            "@var array<string, mixed>|\\stdClass|null",
            (string) (new ReflectionProperty(
                Event::class,
                "minut_metadata",
            ))->getDocComment(),
        );
        $this->assertIsString($methodDoc);
        $this->assertStringContainsString(
            '@param array<string, string|bool>|\stdClass $custom_metadata',
            $methodDoc,
        );
    }

    public function testRecordResponsesPreserveJsonObjects(): void
    {
        $device = Device::from_json(
            (object) [
                "custom_metadata" => (object) [
                    "label" => "front door",
                    "active" => true,
                ],
            ],
        );

        $this->assertNotNull($device);
        $this->assertEquals(
            (object) ["label" => "front door", "active" => true],
            $device->custom_metadata,
        );
    }
}
