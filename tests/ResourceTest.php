<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\Resources\Device;

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

    /**
     * A list of discriminated objects collapses into one class carrying the
     * union of the variant properties, so a field only some variants declare is
     * still readable.
     */
    public function testDiscriminatedListMergesEveryVariantProperty(): void
    {
        $device = $this->device();

        $error = $device->errors[0];

        $this->assertInstanceOf(Device\Errors::class, $error);
        $this->assertSame("device_offline", $error->error_code);
        $this->assertTrue($error->is_device_error);
        $this->assertObjectHasProperty("is_bridge_error", $error);
        $this->assertNull($error->is_bridge_error);
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
    }
}
