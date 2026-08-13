<?php

declare(strict_types=1);

namespace Tests;

use Tests\Support\FakeSeamConnectTestCase;

/**
 * The generated methods drop null parameters from the request payload, so an
 * omitted list and an explicitly empty one must not end up meaning the same
 * thing on the wire.
 */
final class SerializationTest extends FakeSeamConnectTestCase
{
    public function testOmittedArrayParameterIsNotSent(): void
    {
        $devices = $this->seam()->devices->list();

        $this->assertNotEmpty($devices);
    }

    public function testNullArrayParameterIsNotSent(): void
    {
        $devices = $this->seam()->devices->list(device_ids: null);

        $this->assertCount(count($this->seam()->devices->list()), $devices);
    }

    public function testEmptyArrayParameterIsSent(): void
    {
        $devices = $this->seam()->devices->list(device_ids: []);

        $this->assertCount(0, $devices);
    }

    public function testPopulatedArrayParameterFiltersTheResults(): void
    {
        $device_ids = [
            $this->seed["august_device_1"],
            $this->seed["ecobee_device_1"],
        ];

        $devices = $this->seam()->devices->list(device_ids: $device_ids);

        $this->assertCount(2, $devices);

        $returned_ids = array_map(fn($device) => $device->device_id, $devices);

        sort($returned_ids);
        sort($device_ids);

        $this->assertSame($device_ids, $returned_ids);
    }
}
