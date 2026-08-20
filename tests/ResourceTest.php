<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\Resources\Device;
use Seam\Resources\Device\Errors\DeviceOffline;
use Seam\Resources\Device\Errors\ErrorCode;
use Seam\Resources\Device\Properties\AvailableClimatePresets\EcobeeMetadata\Owner;
use Seam\Resources\Device\Properties\Battery\Status;

/**
 * The generated resource classes nest a property's class inside the namespace
 * of the class that owns it, so two properties of the same name at different
 * depths keep their own shapes. These tests pin that down at runtime, since
 * output that is syntactically valid can still autoload to the wrong class.
 */
final class ResourceTest extends TestCase
{
    private function device(): Device
    {
        $device = Device::from_json(
            json_decode(
                json_encode([
                    "properties" => [
                        "battery" => ["level" => 0.4, "status" => "low"],
                        "accessory_keypad" => ["battery" => ["level" => 0.25]],
                    ],
                    "errors" => [
                        [
                            "error_code" => "device_offline",
                            "is_device_error" => true,
                        ],
                    ],
                ]),
            ),
        );

        $this->assertNotNull($device);

        return $device;
    }

    public function testUnknownEnumValuesRemainReadable(): void
    {
        $device = Device::from_json(
            (object) [
                "device_type" => "future_device_type",
            ],
        );

        $this->assertSame("future_device_type", $device->device_type);
    }

    public function testUnknownDiscriminatedValuesUseTheBaseClass(): void
    {
        $device = Device::from_json(
            (object) [
                "errors" => [
                    (object) [
                        "error_code" => "future_error",
                        "message" => "Future error",
                    ],
                ],
            ],
        );
        $error = $device->errors[0];

        $this->assertSame(Device\Errors::class, $error::class);
        $this->assertSame("future_error", $error->error_code);
        $this->assertSame("Future error", $error->message);
    }

    public function testNestedPropertyClassesAreNamespacedByTheirOwner(): void
    {
        $device = $this->device();

        $this->assertInstanceOf(Device\Properties::class, $device->properties);
        $this->assertInstanceOf(
            Device\Properties\Battery::class,
            $device->properties->battery,
        );
        $this->assertInstanceOf(
            Device\Properties\AccessoryKeypad::class,
            $device->properties->accessory_keypad,
        );
        $this->assertInstanceOf(
            Device\Properties\AccessoryKeypad\Battery::class,
            $device->properties->accessory_keypad->battery,
        );
    }

    /**
     * The two batteries used to collapse onto one Seam\Resources\DeviceBattery,
     * and whichever was generated first won, so the device battery lost its
     * status.
     */
    public function testSameNamedPropertiesAtDifferentDepthsKeepTheirShapes(): void
    {
        $device = $this->device();

        $this->assertNotSame(
            Device\Properties\Battery::class,
            Device\Properties\AccessoryKeypad\Battery::class,
        );

        $this->assertSame(0.4, $device->properties->battery->level);
        $this->assertSame("low", $device->properties->battery->status);
        $this->assertSame("low", Status::LOW->value);

        $this->assertSame(
            0.25,
            $device->properties->accessory_keypad->battery->level,
        );
        $this->assertObjectNotHasProperty(
            "status",
            $device->properties->accessory_keypad->battery,
        );
    }

    public function testNestedClassesAreNotDeclaredInTheResourcesNamespace(): void
    {
        $this->assertFalse(
            class_exists("Seam\\Resources\\DeviceBattery"),
            "Nested classes must not be flattened into Seam\\Resources",
        );
        $this->assertFalse(
            class_exists("Seam\\Resources\\DeviceProperties"),
            "Nested classes must not be flattened into Seam\\Resources",
        );
    }

    public function testDiscriminatedListReturnsTheSpecificVariant(): void
    {
        $error = $this->device()->errors[0];

        $this->assertInstanceOf(DeviceOffline::class, $error);
        $this->assertSame("device_offline", $error->error_code);
        $this->assertSame("device_offline", ErrorCode::DEVICE_OFFLINE->value);
        $this->assertTrue($error->is_device_error);
        $this->assertObjectNotHasProperty("is_bridge_error", $error);
    }

    /**
     * Every class in the file has to be registered, because src/Resources is
     * autoloaded by classmap rather than PSR-4.
     */
    public function testNestedClassesAreAutoloadable(): void
    {
        foreach (
            [
                Device\Properties\Battery::class,
                Device\Properties\AccessoryKeypad\Battery::class,
                Device\Properties\AvailableClimatePresets::class,
                Device\Properties\AvailableClimatePresets\EcobeeMetadata::class,
            ]
            as $className
        ) {
            $this->assertTrue(
                class_exists($className),
                "{$className} should be autoloadable",
            );
        }
    }

    /**
     * The climate preset metadata used to take the shape of the device level
     * ecobee_metadata, losing these three fields.
     */
    public function testClimatePresetMetadataKeepsItsOwnShape(): void
    {
        $preset = Device\Properties\AvailableClimatePresets\EcobeeMetadata::from_json(
            json_decode(
                json_encode([
                    "climate_ref" => "sleep",
                    "is_optimized" => true,
                    "owner" => "user",
                ]),
            ),
        );

        $this->assertNotNull($preset);
        $this->assertSame("sleep", $preset->climate_ref);
        $this->assertTrue($preset->is_optimized);
        $this->assertSame("user", $preset->owner);
        $this->assertSame("user", Owner::USER->value);
    }
}
